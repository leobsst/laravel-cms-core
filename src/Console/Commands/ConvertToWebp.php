<?php

namespace Leobsst\LaravelCmsCore\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Leobsst\LaravelCmsCore\Enums\LogStatus;
use Leobsst\LaravelCmsCore\Enums\LogType;
use Leobsst\LaravelCmsCore\Models\Log as LogModel;

class ConvertToWebp extends Command
{
    private array $extensionsGranted = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'svg',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-webp {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all images to webp format from /public/assets/img folder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $publicPath = $this->argument(key: 'path') ?? public_path(path: 'uploads');
        if (file_exists(filename: $publicPath) && is_dir(filename: $publicPath)) {
            $this->info(string: 'Converting images to webp...');
            $this->newLine();
            $log = LogModel::create(attributes: [
                'type' => LogType::CRON->value,
                'message' => 'Converting images to webp...',
                'status' => LogStatus::RUNNING->value,
                'updated_at' => null,
            ]);

            $files = $this->getFilesFromFolder(folder: $publicPath);
            sort($files);
            $count = 0;
            $converted = [];

            try {
                foreach ($files as $file) {
                    // Get the file's extension
                    $extension = pathinfo(path: $file, flags: PATHINFO_EXTENSION);

                    // Define the final path
                    $webpFile = str_replace(search: ".{$extension}", replace: '.webp', subject: $file);

                    // If the file doesn't exist, we convert it
                    if (! file_exists(filename: $webpFile)) {
                        // Get parent directory
                        $directory = dirname($webpFile);

                        // Create the directory if it doesn't exist
                        if (! is_dir(filename: $directory)) {
                            mkdir(directory: $directory, permissions: 0777, recursive: true);
                        }

                        // Convert the image to webp
                        $imgManager = new ImageManager(driver: new Driver);
                        $img = $imgManager->read(input: $file);
                        $img->toWebp()->save(filepath: $webpFile);

                        // Increment the counter
                        $count++;
                        $converted[] = $webpFile;
                    }
                }

                $formatedArray = [
                    'converted_images' => $count,
                    'converted_files' => $converted,
                ];

                $message = 'Images converted to webp successfully.';
                $log->type = LogType::CRON->value;
                $log->status = LogStatus::SUCCESS->value;
                $log->message = $message;
                $log->updated_at = now();
                $log->data = json_encode(value: $formatedArray);
                $log->update();

                dump(vars: $formatedArray);
                Log::info($message . ' ' . $formatedArray['converted_images'] . ' images converted.');
                $this->components->success(string: $message);
            } catch (Exception $e) {
                throw new Exception(message: 'Error while converting images to webp');
            }
        }
    }

    private function getFilesFromFolder(string $folder, ?array $files = null): array
    {
        $files ??= [];

        // Scan the folder
        $paths = scandir(directory: $folder);
        foreach ($paths as $path) {
            // Skip the dots or files/folders starting with a dot
            if (substr(string: $path, offset: 0, length: 1) === '.') {
                continue;
            }
            // Define the path to check
            $checkPath = $folder . DIRECTORY_SEPARATOR . $path;
            if (is_dir(filename: $checkPath)) {
                // If the path is a folder, we scan it
                $files = $this->getFilesFromFolder(folder: $checkPath, files: $files);
            } elseif (in_array(
                needle: pathinfo(path: $checkPath, flags: PATHINFO_EXTENSION),
                haystack: $this->extensionsGranted
            )) {
                // If the path is a file with an extension we want, we add it to the list
                $files[] = $checkPath;
            }
        }

        return $files;
    }
}
