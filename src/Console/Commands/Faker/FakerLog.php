<?php

namespace Leobsst\LaravelCmsCore\Console\Commands\Faker;

use Illuminate\Console\Command;
use Leobsst\LaravelCmsCore\Models\Log;

class FakerLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faker:log {count?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fake page stats';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        collect([
            'Generating fake logs' => fn () => Log::factory()->count($this->argument('count') ?? 5)->create(),
        ])->each(fn ($task, $description) => $this->components->task($description, $task));
    }
}
