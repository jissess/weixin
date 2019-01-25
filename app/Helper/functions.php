<?php
/**
 * Created by PhpStorm.
 * User: wuyejia
 * Date: 2019/1/23
 * Time: 15:59
 */

if (!function_exists('response_success')) {
    function response_success(Array $params = [], $status = 'successful', $code = 1)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $params
        ]);
    }
}

if (!function_exists('response_failed')) {
    function response_failed($message = 'Response Failed', $code = -1)
    {
        return response()->json(['status' => 'failed', 'code' => $code, 'message' => $message]);
    }
}