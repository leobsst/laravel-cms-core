<?php

namespace Leobsst\LaravelCmsCore\Services;

use Exception;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Leobsst\LaravelCmsCore\Models\Setting;

class FilamentService
{
    public static function sendNotification(string $title, bool $success = true, ?string $icon = null, ?string $iconColor = null, ?string $color = null, ?string $body = null): Notification | bool
    {
        if (! is_null(filament()->getCurrentOrDefaultPanel())) {
            $notification = Notification::make()->title($title);

            if ($success) {
                $notification->success();
                if (is_null($color)) {
                    $notification->color('success');
                }
            }
            if (filled($icon)) {
                $notification->icon($icon);
            }
            if (filled($iconColor)) {
                $notification->iconColor($iconColor);
            }
            if (filled($color)) {
                $notification->color($color);
            }
            if (filled($body)) {
                $notification->body($body);
            }

            return $notification->send();
        } else {
            return false;
        }
    }

    public static function getPrimaryColor(): array
    {
        try {
            return Color::generateV3Palette(Setting::get('primary_color'));
        } catch (Exception $e) {
            return Color::Blue;
        }
    }
}
