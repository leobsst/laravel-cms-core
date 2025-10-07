<?php

namespace Leobsst\LaravelCmsCore\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\textarea;

class addChangeLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new change log entry';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info(string: 'Ajout d\'une nouvelle entrée dans le journal des modifications');

        $date = $this->components->ask(question: 'Date de la modification (YYYY-MM-DD)', default: now()->format(format: 'Y-m-d'));

        $description = textarea(
            label: 'Description',
            placeholder: 'Entrez la description de la modification',
            required: true
        );

        $changelogFileMd = base_path(path: 'changelog.md');

        $contentMd = file_exists(filename: $changelogFileMd) ? file_get_contents(filename: $changelogFileMd) : '';
        $contentMd = str_replace(search: "# CHANGELOG\n", replace: '', subject: $contentMd);
        $contentMd = "# CHANGELOG\n\n## $date\n\n$description\n$contentMd";
        fwrite(stream: fopen(filename: $changelogFileMd, mode: 'w'), data: $contentMd);

        $this->components->info(string: 'Entrée ajoutée avec succès');
    }
}
