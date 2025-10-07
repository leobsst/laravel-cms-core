<?php

namespace Leobsst\LaravelCmsCore\Concerns\Api;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * generation de la response json d'erreur
     */
    private function generateError(?string $cause = null, ?array $data = null, int $code = 400): JsonResponse
    {
        return response()->json(data: [
            'status' => 'error',
            'message' => $cause,
            'data' => $data,
        ], status: $code);
    }

    /**
     * generation de la response json de success
     */
    private function generateSuccess(?string $message = null, ?array $data = null, int $code = 200): JsonResponse
    {
        return response()->json(data: [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], status: $code);
    }
}
