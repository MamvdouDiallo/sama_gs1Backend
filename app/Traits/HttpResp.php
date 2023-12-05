<?php

namespace App\Traits;

trait HttpResp
{
    protected function success($code = 200, $message = '', $data = []): array
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
    }

    protected function error($code = 500, $message = null, $data = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data

        ]);
    }
}
