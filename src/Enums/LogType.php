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
            self::WARNING => 'yellow',
            self::CRON => 'yellow',
            self::ERROR => 'danger',
            self::SUCCESS => 'success',
            self::DEBUG => 'gray',
            self::CRITICAL => 'danger',
            self::ALERT => 'danger',
            self::EMERGENCY => 'danger',
        };
    }
}
