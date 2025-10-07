<?php

namespace Leobsst\LaravelCmsCore\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Models\Log as LogModel;

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
    protected $description = 'Terminate all logs older than 24 hours and remove old logs since 3 months';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        collect([
            'Clear logs older than 3 months' => fn () => $this->clearOldLogs() == 0,
            'Terminate logs older than 24 hours' => fn () => $this->terminateOldLogs(),
        ])->each(fn ($task, $description) => $this->components->task($description, $task));
    }

    private function clearOldLogs(): int
    {
        $logs = LogModel::query()->where(column: 'created_at', operator: '<=', value: Carbon::today()->subMonths(3));
        $count = $logs->count();

        $logs->delete();
        $info = 'Successfully cleared ' . $count . ' old logs.';
        Log::info($info);
        $this->components->success(string: $info);

        return 0;
    }

    private function terminateOldLogs(): int
    {
        $logs = LogModel::query()->whereNull(columns: 'updated_at')->where(column: 'created_at', operator: '<=', value: Carbon::today()->subDay());
        $count = $logs->count();

        $logs->each(callback: function ($log) {
            $log->update([
                'status' => LogStatus::ERROR->value,
            ]);
        });
        $info = 'Successfully terminated ' . $count . ' logs.';
        Log::info($info);
        $this->components->success(string: $info);

        return 0;
    }
}
