<?php 

namespace App\Traits\Api;

use Illuminate\Http\JsonResponse;

trait ApiResponse {
    /**
     * generation de la response json d'erreur
     *
     * @param string|null $cause
     * @param array|null $data
     * @param int $code
     * @return JsonResponse
     */
    private function generateError(?string $cause = null, ?array $data = null, int $code = 400): JsonResponse
    {
        return response()->json(data: [
            'status' => 'error',
            'message' => $cause,
            'data'=> $data,
        ], status: $code);
    }

    /**
     * generation de la response json de success
     *
     * @param string|null $message
     * @param array|null $data
     * @param int $code
     * @return JsonResponse
     */
    private function generateSuccess(?string $message = null, ?array $data = null, int $code = 200): JsonResponse
    {
        return response()->json(data: [
            'status' => 'success',
            'message' => $message,
            'data'=> $data,
        ], status: $code);
    }
}