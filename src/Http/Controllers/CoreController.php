<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class CoreController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
