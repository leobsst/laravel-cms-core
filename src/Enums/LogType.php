<?php

namespace Leobsst\LaravelCmsCore\Enums;

enum LogType: string
{
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
    case SUCCESS = 'success';
    case DEBUG = 'debug';
    case CRITICAL = 'critical';
    case ALERT = 'alert';
    case EMERGENCY = 'emergency';
    case CRON = 'cron';

    public function color(): string
    {
        return match ($this) {
            self::INFO => 'info',
            self::WARNING,
            self::CRON => 'yellow',
            self::SUCCESS => 'success',
            self::DEBUG => 'gray',
            self::ERROR,
            self::CRITICAL,
            self::ALERT,
            self::EMERGENCY => 'danger',
        };
    }
}
