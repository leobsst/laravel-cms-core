<?php

namespace Leobsst\LaravelCmsCore\Helpers;

class LogsHelper
{
    public static function convertToJson(?string $trace = null): string
    {
        $result = null;
        // Extract primary error details
        preg_match('/^(?<type>\w+Error): (?<message>.+) in (?<file>.+):(?<line>\d+)/', $trace, $main);

        if (! empty($main)) {
            $result = [
                'error' => [
                    'type' => $main['type'],
                    'message' => $main['message'],
                    'file' => $main['file'],
                    'line' => (int) $main['line'],
                ],
                'stack_trace' => [],
            ];

            // Extract stack trace details
            preg_match_all('/#\d+ (.+)\((\d+)\): (.+)/', $trace, $matches, PREG_SET_ORDER);

            foreach ($matches as $m) {
                $result['stack_trace'][] = [
                    'file' => $m[1],
                    'line' => (int) $m[2],
                    'call' => $m[3],
                ];
            }
        }

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
