<?php

namespace Leobsst\LaravelCmsCore\Livewire;

use Livewire\Component;

class FlashMessage extends Component
{
    public $type;

    public $message;

    protected $listeners = [
        'display-flash-success' => 'forgetCurrent',
        'display-flash-error' => 'forgetCurrent',
        'display-flash-info' => 'forgetCurrent',
    ];

    /**
     * Listen for events to flash message
     */
    public function refreshFlash(): \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View
    {
        if (session()->has($this->type)) {
            $this->dispatch('hide-flash');
            $this->forgetFlash();
            $this->message = session($this->type);
            $this->dispatch('display-flash-' . $this->type);
            // session()->forget($this->type);
        }

        return $this->render();
    }

    /**
     * Forget flash keys and values
     */
    private function forgetFlash(?string $type = null): void
    {
        if ($type) {
            session()->forget($type);
        } else {
            foreach (['success', 'error', 'info'] as $type) {
                if ($type !== $this->type) {
                    session()->forget($type);
                }
            }
        }
    }

    public function forgetCurrent()
    {
        session()->forget($this->type);
    }

    public function getTextColor()
    {
        switch ($this->type) {
            case 'success':
                return 'text-green-800';
            case 'error':
                return 'text-red-800';
            case 'info':
                return 'text-yellow-800';
            default:
                return 'text-gray-500';
        }
    }

    public function render()
    {
        return view('laravel-cms-core::livewire.flash-message');
    }
}
