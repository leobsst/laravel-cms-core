<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers;

use Filament\Facades\Filament;
use Illuminate\Contracts\Session\Session;
use Illuminate\Routing\Controller as BaseController;

class Logout extends BaseController
{
    public function logout()
    {
        App(Session::class)->flush();

        return redirect(Filament::getPanel('dashboard')->getLoginUrl());
    }
}
