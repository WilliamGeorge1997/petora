<?php

namespace Modules\Notification\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Models\Notification;

#[Middleware('auth:client')]
class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = Auth::user()->notifications()
            ->select('id', 'title', 'description', 'subject_id', 'subject_type', 'image', 'created_at', 'read_at')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 10));

        return success(true, __('notification::message.fetched'), $notifications);
    }

    public function allowNotification(): JsonResponse
    {
        $user = Auth::user();
        $user->allow_notification = ! $user->allow_notification;
        $user->save();

        return success(true, __('notification::message.client_updated'));
    }

    public function allow_notification(): JsonResponse
    {
        return $this->allowNotification();
    }

    public function readNotification(Request $request): JsonResponse
    {
        $request->validate([
            'notifications_ids' => ['required', 'array'],
            'notifications_ids.*' => ['integer'],
        ]);

        Auth::user()->notifications()
            ->whereIn('id', $request->validated('notifications_ids'))
            ->update(['read_at' => Carbon::now()]);

        return success(true, __('notification::message.read_success'));
    }

    public function unReadNotificationsCount(): JsonResponse
    {
        $unReadCount = Auth::user()->notifications()
            ->whereNull('read_at')
            ->count();

        return success(true, __('notification::message.unread_count'), ['count' => $unReadCount]);
    }
}
