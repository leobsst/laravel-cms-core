<?php

namespace Leobsst\LaravelCmsCore\Console\Commands\Clean;

use Illuminate\Console\Command;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageStat as Model;

class PageStat extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'clean:page-stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean page stats';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Model::all()->each(fn ($stat) => $stat->delete());
    }
}
