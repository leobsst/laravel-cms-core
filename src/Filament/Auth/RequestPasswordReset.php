<?php

namespace Leobsst\LaravelCmsCore\Filament\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Password;
use Leobsst\LaravelCmsCore\Models\UserEmail;
use Leobsst\LaravelCmsCore\Notifications\ResetPasswordNotification;

class RequestPasswordReset extends \Filament\Auth\Pages\PasswordReset\RequestPasswordReset
{
    public function request(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return;
        }

        $data = $this->form->getState();
        $userEmails = UserEmail::where(column: 'email', operator: $data['email']);
        $status = 'Une erreur est survenue.';
        if ($userEmails->exists()) {
            $data['email'] = $userEmails->first()->user->email;
            $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
                $data,
                function (CanResetPassword $user, string $token): void {
                    if (! method_exists($user, 'notify')) {
                        $userClass = $user::class;

                        throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                    }

                    $notification = new ResetPasswordNotification(
                        token: $token,
                        subject: 'Demande de réinitialisation de mot de passe',
                        action: 'Réinitialiser mon mot de passe',
                        l1: 'Vous avez demandé à réinitialiser votre mot de passe.',
                        l2: 'Pour continuer, veuillez cliquer sur le bouton ci-dessous afin de réinitialiser votre mot de passe.'
                    );
                    $user->notify($notification);
                },
            );
        }

        if ($status !== Password::RESET_LINK_SENT) {
            Notification::make()
                ->title(__($status))
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Un email de réinitialisation de mot de passe vous a été envoyé.')
            ->success()
            ->send();

        redirect(Filament::getLoginUrl());
    }
}
