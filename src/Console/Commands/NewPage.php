<?php

namespace Leobsst\LaravelCmsCore\Console\Commands;

use Illuminate\Console\Command;
use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;

use function Laravel\Prompts\textarea;

class NewPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:page-seeder {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new page seeder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        setlocale(LC_ALL, 'en_US.utf8');
        if ($this->argument('name')) {
            $name = str_replace(['/', '\\'], '', $this->argument('name'));
        } else {
            $name = str_replace(['/', '\\'], '', $this->ask('Entrez le nom de la page'));
        }

        $fileName = str_replace([' ', "'"], '', ucwords(str_replace(['-', '_'], ' ', $name)));
        $fileName = urlencode(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $fileName));

        while (file_exists(database_path('seeders/insert' . $fileName . 'PageSeeder.php'))) {
            $name = str_replace(['/', '\\'], '', $this->ask('Le fichier existe déjà. Entrez un autre nom'));
            $fileName = str_replace([' ', "'"], '', ucwords(str_replace(['-', '_'], ' ', $name)));
            $fileName = urlencode(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $fileName));
        }

        $slug = Page::cleanSlug(str_replace(['/', '\\'], '', $this->ask('Entrez la slug de la page')));
        $keywords = explode(' ', $this->ask('Entrez les mots-clés de la page (séparés par un espace)'));
        $description = $this->ask('Entrez la description de la page');
        $is_published = $this->confirm('La page est-elle publiée ?', true);
        $is_default = $this->confirm('La page est-elle par défaut ?');

        $pageContent = str_replace("'", "\'", textarea(
            'Contenu de la page (HTML)',
            'Entrez le contenu de la page',
            required: true
        ));

        $this->info('Generating new page seeder...');

        $content = "<?php\n";
        $content .= "\n";
        $content .= "namespace Database\Seeders;\n";
        $content .= "\n";
        $content .= "use Leobsst\LaravelCmsCore\Models\Features\Pages\Page;\n";
        $content .= "use Leobsst\LaravelCmsCore\Models\Features\Pages\PagesSeo;\n";
        $content .= "use Illuminate\Database\Seeder;\n";
        $content .= "\n";
        $content .= 'class insert' . $fileName . "PageSeeder extends Seeder\n";
        $content .= "{\n";
        $content .= "    /**\n";
        $content .= "     * Run the database seeds.\n";
        $content .= "     */\n";
        $content .= "    public function run(): void\n";
        $content .= "    {\n";
        $content .= "        \$page = Page::create([\n";
        $content .= "            'title' => '" . $name . "',\n";
        $content .= "            'slug' => '" . $slug . "',\n";
        $content .= "            'content' => '" . $pageContent . "',\n";
        $content .= "            'is_home' => false,\n";
        $content .= "            'is_published' => " . ($is_published ? 'true' : 'false') . ",\n";
        $content .= "            'is_default' => " . ($is_default ? 'true' : 'false') . ",\n";
        $content .= "            'published_at' => now(),\n";
        $content .= "            'created_at' => now(),\n";
        $content .= "            'updated_at' => now(),\n";
        $content .= "        ]);\n";
        $content .= "\n";
        $content .= "        PagesSeo::create([\n";
        $content .= "            'page_id' => \$page->id,\n";
        $content .= "            'title' => \$page->title,\n";
        $content .= "            'description' => '" . $description . "',\n";
        $content .= "            'tags' => ['" . implode("', '", $keywords) . "'],\n";
        $content .= "            'robots' => 'index, follow',\n";
        $content .= "            'og_type' => 'website',\n";
        $content .= "            'og_locale' => 'fr_FR',\n";
        $content .= "            'twitter_card' => 'summary_large_image',\n";
        $content .= "        ]);\n";
        $content .= "    }\n";
        $content .= "}\n";
        file_put_contents(database_path('seeders/insert' . $fileName . 'PageSeeder.php'), $content);

        $this->newLine();
        $this->info('Seeder generated successfully.');
        $this->newLine();
        $this->info('Adding seeder to DatabaseSeeder...');
        $this->newLine();

        $dbSeeder = file_get_contents(database_path('seeders/DatabaseSeeder.php'));
        $dbSeeder = str_replace('/* PageUse */', "/* PageUse */\nuse Database\\Seeders\\insert" . $fileName . 'PageSeeder;', $dbSeeder);
        $dbSeeder = str_replace('/* Pages */', "/* Pages */\n            insert" . $fileName . 'PageSeeder::class,', $dbSeeder);
        file_put_contents(database_path('seeders/DatabaseSeeder.php'), $dbSeeder);

        $this->newLine(2);
        $this->info('Files generated successfully.');

        return Command::SUCCESS;
    }
}
