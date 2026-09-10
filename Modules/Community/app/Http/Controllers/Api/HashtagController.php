<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\Services\HashtagService;

#[Middleware('auth:client')]
class HashtagController extends Controller
{
    public function __construct(private HashtagService $hashtagService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $hashtags = $this->hashtagService->active($data);

        return success(true, __('community::message.hashtag.fetched'), $hashtags);
    }

    public function show(int $hashtag_id)
    {
        $hashtag = $this->hashtagService->findById($hashtag_id, ['posts']);

        return success(true, __('community::message.hashtag.fetched'), $hashtag);
    }
}
