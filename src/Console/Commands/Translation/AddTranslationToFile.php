<?php

namespace Leobsst\LaravelCmsCore\Console\Commands\Translation;

use Illuminate\Console\Command;

class AddTranslationToFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:make {name? : Nom du fichier de traduction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill translations files.';

    /**
     * Execute the console command.
     *
     * @param  string  $path
     */
    public function handle()
    {
        if ($this->argument('name')) {
            $name = $this->argument('name');
        } else {
            $name = $this->ask('Entrer le nom du fichier de traduction');
        }
        $languages = $this->choice(
            question: 'Sélectionner les langues (séparées par des virgules)',
            choices: ['fr', 'en'],
            default: '0,1',
            multiple: true
        );
        $path = base_path('lang');

        while (substr($name, 0, 1) === '/') {
            $name = substr($name, 1);
        }

        while (substr($name, -1) === '/') {
            $name = substr($name, 0, -1);
        }

        if (str_contains($name, '/')) {
            $fileName = explode('/', $name);
            $name = end($fileName);
            array_pop($fileName);
        } else {
            $fileName = [];
        }

        $translations = [];
        $newKey = str_replace("\'", "'", $this->ask('Entrer la nouvelle clé de traduction'));

        while (trim(strlen($newKey)) === 0 || is_null($newKey)) {
            $newKey = str_replace("\'", "'", $this->ask('Entrer la nouvelle clé de traduction'));
        }

        while ($this->confirm('Confirmer la clé de traduction ' . $newKey . ' ?', true) === false) {
            $newKey = str_replace("\'", "'", $this->ask('Entrer la nouvelle clé de traduction'));
        }

        foreach ($languages as $language) {
            $newPath = $path . '/' . $language . '/' . implode('/', $fileName);

            if (! is_dir($newPath)) {
                return $this->error('Le dossier ' . $newPath . ' n\'existe pas.');
            }

            $file = $newPath . '/' . $name . '.php';
            if (! file_exists($file)) {
                return $this->error('Le fichier ' . $file . ' n\'existe pas.');
            } else {
                $translations[$language] = require $file;
                $translations[$language][$newKey] = str_replace("\'", "'", $this->ask('Entrer la traduction pour ' . $language));
                ksort($translations[$language]);
                $content = "<?php\nreturn [\n";
                foreach ($translations[$language] as $key => $value) {
                    $content .= "    '" . str_replace("'", "\'", $key) . "' => '" . str_replace("'", "\'", $value) . "',\n";
                }
                $content .= "];\n";
                file_put_contents($file, $content);
            }
        }

        $this->info('Files generated successfully.');

        return Command::SUCCESS;
    }
}
