<?php

namespace Leobsst\LaravelCmsCore\Console\Commands;

use Carbon\Carbon;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Models\Log as LogModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TerminateLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:terminate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terminate all logs older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(string: 'Terminating logs older than 24 hours...');
        $logs = LogModel::whereNull(columns: 'updated_at')->where(column: 'created_at', operator: '<=', value: Carbon::today()->subDay());
        $count = $logs->count();

        $logs->each(callback: function ($log) {
            $log->update([
                'status' => LogStatus::ERROR->value,
            ]);
        });
        $info = 'Successfully terminated ' . $count . ' logs.';
        Log::info($info);
        $this->components->success(string: $info);
    }
}
