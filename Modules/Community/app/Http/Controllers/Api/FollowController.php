<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\Http\Requests\ToggleFollowRequest;
use Modules\Community\Services\FollowService;
use Modules\Community\Transformers\FollowResource;

#[Middleware('auth:client', only: ['toggle'])]
class FollowController extends Controller
{
    public function __construct(private FollowService $followService) {}

    public function followers(Request $request, int $client_id)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'following_id' => $client_id])->all();
        $followers = $this->followService->findAll($data, ['follower:id,name,image']);

        return success(true, __('community::message.follow.followers_fetched'), paginatedResource($followers, FollowResource::class));
    }

    public function following(Request $request, int $client_id)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'follower_id' => $client_id])->all();
        $following = $this->followService->findAll($data, ['following:id,name,image']);

        return success(true, __('community::message.follow.following_fetched'), paginatedResource($following, FollowResource::class));
    }

    public function toggle(ToggleFollowRequest $request, int $client_id)
    {
        $follower_id = auth('client')->id();
        $follow = $this->followService->toggleFollow($follower_id, $client_id);
        $message = $follow ? __('community::message.follow.created') : __('community::message.follow.deleted');

        return success(true, $message, $follow);
    }
}
