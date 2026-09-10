<?php

namespace Modules\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\Models\Hashtag;
use Modules\Community\Services\HashtagService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-hashtag', only: ['index'])]
#[Middleware('permission:Edit-hashtag', only: ['activate'])]
#[Middleware('permission:Delete-hashtag', only: ['destroy'])]
class HashtagController extends Controller
{
    public function __construct(private HashtagService $hashtagService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $hashtags = $this->hashtagService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('community::general.hashtag.fetched'), $hashtags->items());
        }

        return view('community::hashtags.index', compact('hashtags'));
    }

    public function destroy(Hashtag $hashtag)
    {
        $this->hashtagService->delete($hashtag);

        return success(true, __('community::general.hashtag.deleted'));
    }

    public function activate(Hashtag $hashtag)
    {
        $hashtag = $this->hashtagService->activate($hashtag);

        return success(
            true,
            $hashtag->is_active ? __('community::general.hashtag.activated') : __('community::general.hashtag.deactivated'),
            $hashtag
        );
    }
}
