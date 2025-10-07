<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers\FileExplorer;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class OpenFileController extends Controller
{
    public function __invoke(Request $request)
    {
        $disk = $request->query('disk');
        $path = $request->query('path');

        abort_unless(is_string($disk) && is_string($path), 404);

        // Sécurise: disque doit être autorisé par la config
        $allowedDisks = (array) Config::get('core.features.file_explorer.disks', []);
        abort_unless(in_array($disk, $allowedDisks, true), 403);

        /** @var FilesystemAdapter $adapter */
        $adapter = Storage::disk($disk);

        abort_unless($adapter->exists($path), 404);

        // Stream inline si possible (images/pdf s'ouvrent, autres selon navigateur)
        return $adapter->response($path);
    }
}
