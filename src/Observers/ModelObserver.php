<?php

namespace Leobsst\LaravelCmsCore\Observers;

use Leobsst\LaravelCmsCore\Traits\LogModelTransactionsTrait;
use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    use LogModelTransactionsTrait;

    /**
     * Handle the User "created" event.
     */
    public function created(Model $model): void
    {
        $this->log(
            model: $model,
            message: 'Un enregistrement a été créé',
        );
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(Model $model): void
    {
        $this->log(
            model: $model,
            message: 'Un enregistrement a été modifié',
        );
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->log(
            model: $model,
            message: 'Un enregistrement a été supprimé',
        );
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(Model $model): void
    {
        $this->log(
            model: $model,
            message: 'Un enregistrement a été restauré',
        );
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(Model $model): void
    {
        $this->log(
            model: $model,
            message: 'Un enregistrement a été supprimé définitivement',
        );
    }
}
