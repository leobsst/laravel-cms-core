<?php

namespace Leobsst\LaravelCmsCore\Observers;

use Leobsst\LaravelCmsCore\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $user->emails()->create(attributes: [
            'email' => $user->email,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        $user->emails()->where('email', $user->getOriginal('email'))->update([
            'email' => $user->email,
            'email_verified_at' => now(),
        ]);
    }
}
