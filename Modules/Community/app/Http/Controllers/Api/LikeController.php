<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\LikeDto;
use Modules\Community\Http\Requests\LikeRequest;
use Modules\Community\Models\Like;
use Modules\Community\Services\LikeService;
use Illuminate\Support\Facades\Gate;

#[Middleware('auth:client')]
class LikeController extends Controller
{
    public function __construct(private LikeService $likeService) {}

    public function index(Request $request, int $post_id)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'likeable_id' => $post_id, 'likeable_type' => \Modules\Community\Models\Post::class])->all();
        $likes = $this->likeService->findAll($data, ['client']);
        return success(true, __('community::message.like.fetched'), $likes);
    }

    public function toggle(Request $request, int $post_id)
    {
        $client_id = auth('client')->id();
        $like = $this->likeService->toggleLike($client_id, $post_id, \Modules\Community\Models\Post::class);
        $message = $like ? __('community::message.like.created') : __('community::message.like.deleted');
        return success(true, $message, $like);
    }
}

