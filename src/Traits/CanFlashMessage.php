<?php

namespace Leobsst\LaravelCmsCore\Traits;

trait CanFlashMessage
{
    public function flash($type, $message)
    {
        session()->put($type, $message);
        $this->dispatch('refresh-flash-'.$type);
    }
}
