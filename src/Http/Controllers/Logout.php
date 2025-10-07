<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers;

use Filament\Facades\Filament;
use Illuminate\Contracts\Session\Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Logout extends BaseController
{
    public function logout()
    {
        if (Auth::check()) {
            App(Session::class)->flush();

            return redirect(Filament::getPanel('dashboard')->getLoginUrl());
        }
    }
}
