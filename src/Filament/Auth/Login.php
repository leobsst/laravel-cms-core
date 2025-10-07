<?php

namespace Leobsst\LaravelCmsCore\Filament\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\MultiFactor\Contracts\HasBeforeChallengeHook;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Models\Log;
use Leobsst\LaravelCmsCore\Models\UserEmail;
use Leobsst\LaravelCmsCore\Services\ClientService;

class Login extends \Filament\Auth\Pages\Login
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();
        $userEmails = UserEmail::where(column: 'email', operator: $data['email']);

        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();

        $authProvider = $authGuard->getProvider(); /** @phpstan-ignore-line */
        $credentials = array_merge($this->getCredentialsFromFormData([
            'email' => $userEmails->first()?->user?->email ?? $data['email'],
            'password' => $data['password'],
        ]));

        $user = $authProvider->retrieveByCredentials($credentials);

        if ((! $user) || (! $authProvider->validateCredentials($user, $credentials))) {
            $this->userUndertakingMultiFactorAuthentication = null;

            if ($userEmails->exists()) {
                $user = $userEmails->first()->user;
                $user->log(
                    LogType::WARNING,
                    'L\'utilisateur "' . $user->first()->name . '" a tenté de se connecter mais n\'a pas réussi avec les identifiants fournis.',
                    LogStatus::ERROR
                );
            } else {
                Log::create([
                    'type' => LogType::WARNING,
                    'message' => 'Tentative de connexion échouée avec l\'adresse email "' . $data['email'] . '".',
                    'status' => LogStatus::ERROR,
                    'data' => null,
                    'ip_address' => ClientService::getIp(),
                ]);
            }

            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        if (
            filled($this->userUndertakingMultiFactorAuthentication) &&
            (decrypt($this->userUndertakingMultiFactorAuthentication) === $user->getAuthIdentifier())
        ) {
            $this->multiFactorChallengeForm->validate();
        } else {
            foreach (Filament::getMultiFactorAuthenticationProviders() as $multiFactorAuthenticationProvider) {
                if (! $multiFactorAuthenticationProvider->isEnabled($user)) {
                    continue;
                }

                $this->userUndertakingMultiFactorAuthentication = encrypt($user->getAuthIdentifier());

                if ($multiFactorAuthenticationProvider instanceof HasBeforeChallengeHook) {
                    $multiFactorAuthenticationProvider->beforeChallenge($user);
                }

                break;
            }

            if (filled($this->userUndertakingMultiFactorAuthentication)) {
                $this->multiFactorChallengeForm->fill();

                return null;
            }
        }

        if (! $authGuard->attemptWhen(array_merge($credentials, ['enabled' => 1]), function (Authenticatable $user): bool {
            if (! ($user instanceof FilamentUser)) {
                return true;
            }

            $user->log(
                LogType::SUCCESS,
                'L\'utilisateur "' . $user->name . '" s\'est connecté.',
                LogStatus::SUCCESS
            );

            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $data['remember'] ?? false)) {
            if ($userEmails->exists() && $userEmails->first()->user->enabled == 0) {
                $user = $userEmails->first()->user;
                $user->log(
                    LogType::WARNING,
                    'L\'utilisateur "' . $user->first()->name . '" a tenté de se connecter mais son compte est désactivé.',
                    LogStatus::ERROR
                );

                throw ValidationException::withMessages([
                    'data.email' => new HtmlString('Votre compte a été désactivé.<br>Veuillez contacter un administrateur pour plus d\'informations.'),
                ]);
            }

            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
