<?php

use Modules\Order\Entities\History;

function return_msg(bool $status = false, string $msg = null, $data = null, string $status_string = "ok")
{
    $newData = ['status' => $status, 'msg' => $msg, 'data' => $data];
    return response($newData, getStatusCode($status_string));
}

function getStatusCode($type = "ok")
{
    return allStatusCode()[strtolower($type)] ?? 200;
}

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

function getSetting($key)
{
    return \Modules\Common\Entities\Setting::where('key', $key)->first()['value'];
}
function getSettingsIn($keys)
{
    return \Modules\Common\Entities\Setting::whereIn('key', $keys)->get()->pluck('value', 'key')->toArray();
}

function saveHistory($data, $model)
{
    History::create([
        'order_status_id' => $data['order_status_id'],
        'order_id' => $data['order_id'],
        'notes' => @$data['notes'],
        'historible_id' => $data['user_id'] ?? null,
        'historible_type' => $model
    ]);
}
function pushOrderStatusNotify($order)
{
    $options = array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true
    );
    $pusher = new Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );
    return $pusher->trigger('order-status-notify-channel', 'Modules\\Order\\Events\\OrderStatusNotify', ['order_id' => $order->id, 'order_status_id' => $order->order_status_id]);
}

function getCaseCollection($builder, array $data)
{
    if ($data['paginated'] ?? null) {
        return $builder->paginate($data['paginated'] ?? 20);
    }
    return $builder->get();
}

function getBranchId($request)
{
    $admin = auth('admin')->user();
    if ($admin->hasRole('Super Admin'))
        if ($request->filled('branch_id'))
            return $request->branch_id;
    return $admin->branch_id;
}

function pushBranchRegistrationNotify($registration)
{
    $options = array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true
    );
    $pusher = new Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );
    return $pusher->trigger('branch-registration-notify-channel', 'Modules\\Branch\\Events\\BranchRegistrationNotify', [
        'id' => $registration->id,
        'name' => $registration->name
    ]);
}

function pushCallWaiterNotify($callWaiter)
{
    $options = array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true
    );
    $pusher = new Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );
    return $pusher->trigger('call-waiter-notify-channel-' . $callWaiter->branch_id, 'Modules\\Branch\\Events\\CallWaiterNotify', [
        'id' => $callWaiter->id,
        'table' => $callWaiter->table,
        'branch_id' => $callWaiter->branch_id
    ]);
}
