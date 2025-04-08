<?php

namespace Leobsst\LaravelCmsCore\Filament\Auth;

use Filament\Facades\Filament;
use Leobsst\LaravelCmsCore\Models\Log;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Filament\Pages\Auth\Login as BaseAuth;
use Filament\Models\Contracts\FilamentUser;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Models\UserEmail;
use Illuminate\Validation\ValidationException;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class Login extends BaseAuth
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
        if (!$userEmails->exists() || !Filament::auth()->attempt(credentials: array_merge($this->getCredentialsFromFormData(
                data: [
                    'email' => $userEmails->first()->user->email,
                    'password' => $data['password'],
                ]), ['enabled' => 1]),
                remember: $data['remember'] ?? false)) {
            if ($userEmails->exists() && $userEmails->first()->user->enabled == 0) {
                $user = $userEmails->first()->user;
                $user->log(
                    LogType::WARNING,
                    'L\'utilisateur "' . $user->first()->name . '" a tenté de se connecter mais son compte est désactivé.',
                    LogStatus::ERROR
                );
                throw ValidationException::withMessages([
                    'data.email' => 'Votre compte a été désactivé. Veuillez contacter un administrateur pour plus d\'informations.',
                ]);
            } elseif ($userEmails->exists()) {
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
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        $user->log(
            LogType::SUCCESS,
            'L\'utilisateur "' . $user->name . '" s\'est connecté.',
            LogStatus::SUCCESS
        );

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
