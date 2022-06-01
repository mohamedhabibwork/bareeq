<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param string $message
     * @param array $error
     * @param int $code
     * @return JsonResponse
     */
    public static function error(string $message, array $error = [], int $code = 400): JsonResponse
    {
        $status = false;
        return response()->json(compact('message', 'error', 'status'), $code);
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public static function success(string $message, array $data = [], int $code = 200): JsonResponse
    {
        $status = true;
        return response()->json(compact('message', 'data', 'status'), $code);
    }

    /**
     * @return never
     */
    public static function notFound()
    {
        return abort(404, 'Element');
    }

}
