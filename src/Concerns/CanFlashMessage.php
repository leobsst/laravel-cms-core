<?php

namespace Leobsst\LaravelCmsCore\Concerns;

trait CanFlashMessage
{
    public function flash($type, $message)
    {
        session()->put($type, $message);
        $this->dispatch('refresh-flash-' . $type);
    }
}
