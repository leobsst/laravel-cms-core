<?php

namespace Leobsst\LaravelCmsCore\Enums\Features\FileExplorer;

enum FileExplorerItemTypeEnum: string
{
    case PARENT = 'parent';
    case FILE = 'file';
    case FOLDER = 'folder';
    case ACTION = 'action';
    case INDEX = 'index';
}
