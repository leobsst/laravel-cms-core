<?php

namespace Leobsst\LaravelCmsCore\Console\Commands\Translation;

use Illuminate\Console\Command;

class Translate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:translate {languages=fr,en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new translations to existing JSON files.';

    /**
     * Execute the console command.
     *
     * @param  string  $languages  (default: fr,es)
     */
    public function handle()
    {
        $languages = explode(',', $this->argument('languages'));
        $path = base_path('lang');

        $newKey = str_replace("\'", "'", $this->ask('Entrer la nouvelle clé de traduction'));

        while (trim(strlen($newKey)) === 0 || is_null($newKey)) {
            $newKey = str_replace("\'", "'", $this->ask('Entrer la nouvelle clé de traduction'));
        }

        while ($this->confirm('Confirmer la clé de traduction ' . $newKey . ' ?', true) === false) {
            $newKey = str_replace("\'", "'", $this->ask('Entrer la nouvelle clé de traduction'));
        }
        foreach ($languages as $language) {
            $file = $path . '/' . $language . '.json';
            if (file_exists($file)) {
                $content = file_get_contents($file);
                $json = json_decode($content, true);
            } else {
                $json = [];
            }

            $json[$newKey] = str_replace("\'", "'", $this->ask('Entrer la traduction pour ' . $language));
            ksort($json);
            file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        $this->components->info('New translation added successfully.');

        return Command::SUCCESS;
    }
}
