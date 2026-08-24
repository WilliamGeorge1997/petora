<?php


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Modules\Common\Models\Setting;


if (!function_exists('allStatusCode')) {
    function allStatusCode()
    {
        return [
            "ok" => 200,
            "created" => 201,
            "accepted" => 202,
            "no_content" => 204,
            "moved" => 301,
            "found" => 302,
            "see_other" => 303,
            "not_modified" => 304,
            "temporary_redirect" => 307,
            "bad_request" => 400,
            "unauthorized" => 401,
            "forbidden" => 403,
            "not_found" => 404,
            "method_not_allowed" => 405,
            "not_acceptable" => 406,
            "precondition_failed" => 412,
            "unsupported_media_type" => 415,
            "validation_error" => 422,
            "server_error" => 500,
            "not_implemented" => 501,
        ];
    }
}

if (!function_exists('getStatusCode')) {
    function getStatusCode($type = "ok")
    {
        return allStatusCode()[strtolower($type)] ?? 200;
    }
}

if (!function_exists('success')) {
    function success(bool $status, string $message, $data = null, string $status_string = 'ok'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], getStatusCode($status_string));
    }
}

if (!function_exists('fail')) {
    function fail(bool $status, string $message, $data = null, string $status_string = 'bad_request'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], getStatusCode($status_string));
    }
}

if (!function_exists('getCaseCollection')) {
    function getCaseCollection(Builder $builder, array $data)
    {
        if ($data['paginated'] ?? null) {
            return $builder->paginate($data['paginated'] ?? 20);
        }
        return $builder->get();
    }
}

if (!function_exists('getSetting')) {
    function getSetting(string $key)
    {
        return Setting::where('key', $key)->value('value');
    }
}

if (!function_exists('getSettings')) {
    function getSettings(array $keys)
    {
        return Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
    }
}
