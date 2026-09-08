<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\LikeDto;
use Modules\Community\Http\Requests\LikeRequest;
use Modules\Community\Services\LikeService;

#[Middleware('auth:client')]
class LikeController extends Controller
{
    public function __construct(private LikeService $likeService) {}

    public function index(LikeRequest $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $likes = $this->likeService->findAll($data, ['client']);

        return success(true, __('community::message.like.fetched'), $likes);
    }

    public function toggle(LikeRequest $request)
    {
        $dto = LikeDto::fromRequest($request);
        $like = $this->likeService->toggle($dto);
        $message = $like ? __('community::message.like.created') : __('community::message.like.deleted');

        return success(true, $message, $like);
    }
}

