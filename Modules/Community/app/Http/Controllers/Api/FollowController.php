<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\FollowDto;
use Modules\Community\Http\Requests\FollowRequest;
use Modules\Community\Models\Follow;
use Modules\Community\Services\FollowService;
use Illuminate\Support\Facades\Gate;

#[Middleware('auth:client')]
class FollowController extends Controller
{
    public function __construct(private FollowService $followService) {}

    public function followers(Request $request, int $user_id)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'following_id' => $user_id])->all();
        $followers = $this->followService->findAll($data, ['follower']);
        return success(true, __('community::message.follow.fetched'), $followers);
    }

    public function following(Request $request, int $user_id)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'follower_id' => $user_id])->all();
        $following = $this->followService->findAll($data, ['following']);
        return success(true, __('community::message.follow.fetched'), $following);
    }

    public function toggle(Request $request, int $user_id)
    {
        $follower_id = auth('client')->id();
        $follow = $this->followService->toggleFollow($follower_id, $user_id);
        $message = $follow ? __('community::message.follow.created') : __('community::message.follow.deleted');
        return success(true, $message, $follow);
    }
}
