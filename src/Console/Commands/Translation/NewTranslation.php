<?php

namespace Leobsst\LaravelCmsCore\Console\Commands\Translation;

use Illuminate\Console\Command;

class NewTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:new {name} {languages=fr,en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new files for translation.';

    /**
     * Execute the console command.
     *
     * @param  string  $name
     * @param  string  $languages  (default: fr,es)
     */
    public function handle()
    {
        $languages = explode(',', $this->argument('languages'));
        $path = base_path('lang');
        $name = $this->argument('name');

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

        $originPath = $path;

        foreach ($languages as $language) {
            if (! is_dir($originPath . '/' . $language)) {
                // mkdir($originPath . '/' . $language);
                $path = $originPath . '/' . $language;
            }

            $newPath = $path . '/' . implode('/', $fileName);

            if (! is_dir($newPath)) {
                mkdir(directory: $newPath, recursive: true);
            }

            $file = $newPath . '/' . $name . '.php';
            if (! file_exists($file)) {
                $content = "<?php\nreturn [\n    // 'key' => 'value',\n];";
                file_put_contents($file, $content);
            }
        }

        Command::info('Files generated successfully. [' . implode(', ', $languages) . ']');

        return Command::SUCCESS;
    }
}
