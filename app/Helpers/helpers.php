<?php


if (! function_exists('formatResponse')) {
    function formatResponse($data, bool $status = true, string $message = ''): array 
    { 
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
    }
}
