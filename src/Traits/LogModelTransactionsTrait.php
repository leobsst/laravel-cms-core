<?php

namespace Leobsst\LaravelCmsCore\Traits;

use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Models\Log as LogModel;
use Leobsst\LaravelCmsCore\Services\ClientService;
use Illuminate\Database\Eloquent\Model;

trait LogModelTransactionsTrait
{
    protected function log(Model $model, string $message, $table = null, $id = null)
    {
        $table = $table ?: $model->getTable();
        $id = $id ?: $model->id;

        LogModel::create(attributes: [
            'creator_id' => auth()->id(),
            'type' => LogType::SUCCESS->value,
            'message' => $message,
            'reference_table' => $table,
            'reference_id' => $id,
            'status' => LogStatus::SUCCESS->value,
            'ip_address' => ClientService::getIp(),
        ]);
    }
}
