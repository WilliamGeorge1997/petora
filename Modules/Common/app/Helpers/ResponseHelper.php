<?php


use \Illuminate\Contracts\Pagination\CursorPaginator;
use \Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
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
    function fail(bool $status, string $message, mixed $errors = null, string $status_string = 'bad_request'): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
        ], getStatusCode($status_string));
    }
}

if (!function_exists('paginatedResource')) {
    function paginatedResource(LengthAwarePaginator|CursorPaginator|Collection $data, string $resourceClass)
    {
        if ($data instanceof Paginator || $data instanceof CursorPaginator) {
            $data->getCollection()->transform(fn($item) => (new $resourceClass($item))->resolve());
            return $data;
        }

        if ($data instanceof Collection) {
            return $data->map(fn($item) => (new $resourceClass($item))->resolve());
        }

        return $data;
    }
}

if (!function_exists('getCaseCollection')) {
    function getCaseCollection(Builder $builder, array $data, array $columns = ['*'])
    {
        if ($data['paginated'] ?? null) {
            $type = $data['pagination_type'] ?? 'default';
            $perPage = is_numeric($data['paginated']) ? $data['paginated'] : 50;

            if ($type === 'cursor') {
                return $builder->cursorPaginate($perPage, $columns);
            }
            if ($type === 'simple') {
                return $builder->simplePaginate($perPage, $columns);
            }

            return $builder->paginate($perPage, $columns);
        }
        return $builder->get($columns);
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

if (!function_exists('haversineSql')) {
    function haversineSql(string $latitudeColumn = 'latitude', string $longitudeColumn = 'longitude'): string
    {
        return "( 
            6371 * acos( 
                cos( radians(?) ) * 
                cos( radians( {$latitudeColumn} ) ) * 
                cos( radians( {$longitudeColumn} ) - radians(?) ) + 
                sin( radians(?) ) * 
                sin( radians( {$latitudeColumn} ) ) 
            ) 
        )";
    }
}

if (!function_exists('nearest')) {
    function nearest(
        Builder $query,
        float|string $latitude,
        float|string $longitude,
        float|int|string|null $radius = null,
        string $latitudeColumn = 'latitude',
        string $longitudeColumn = 'longitude'
    ): Builder {

        $radius = $radius ?? 50;
        $sql = haversineSql($latitudeColumn, $longitudeColumn);

        return $query->whereNotNull($latitudeColumn)
            ->whereNotNull($longitudeColumn)
            ->selectRaw("*, $sql AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }
}

if (!function_exists('isVideo')) {
    function isVideo(UploadedFile $file): bool
    {
        if ($file instanceof UploadedFile) {
            return str_starts_with($file->getMimeType(), 'video/');
        }

        if (is_string($file)) {
            $extension = strtolower(pathinfo(parse_url($file, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
            return in_array($extension, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv', 'webm', '3gp', 'm4v'], true);
        }

        return false;
    }
}
