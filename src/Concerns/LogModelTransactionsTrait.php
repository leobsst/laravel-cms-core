<?php

namespace Leobsst\LaravelCmsCore\Concerns;

use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Models\Log as LogModel;
use Leobsst\LaravelCmsCore\Services\ClientService;

trait LogModelTransactionsTrait
{
    protected function log(Model $model, string $message, $table = null, $id = null)
    {
        $table = $table ?: $model->getTable();
        $id = $id ?: ($model->getKey() ?? null);

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
