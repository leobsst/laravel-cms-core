<?php

namespace Leobsst\LaravelCmsCore\Jobs;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Leobsst\LaravelCmsCore\Models\Log;
use Leobsst\LaravelCmsCore\Models\User;

class SendLogs implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(private Authenticatable | User $user) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::sendLogsToEmail($this->user);
    }
}
