<?php

namespace Leobsst\LaravelCmsCore\Observers;

use Leobsst\LaravelCmsCore\Models\User;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @return void
     */
    public function creating(User $user)
    {
        if (filled(value: $user->extra_data) && is_array(value: $user->extra_data)) {
            $user->extra_data = serialize($user->extra_data);
        }
    }

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
        if (filled(value: $user->extra_data) && is_array(value: $user->extra_data)) {
            $user->extra_data = serialize($user->extra_data);
        }

        $user->emails()->where('email', $user->getOriginal('email'))->update([
            'email' => $user->email,
            'email_verified_at' => now(),
        ]);
    }
}
