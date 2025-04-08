<?php

namespace Leobsst\LaravelCmsCore\Enums;

enum LogStatus: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case PENDING = 'pending';
    case RUNNING = 'running';

    public function color(): string
    {
        return match ($this) {
            self::SUCCESS => 'success',
            self::ERROR => 'danger',
            self::PENDING => 'gray',
            self::RUNNING => 'warning',
        };
    }
}
