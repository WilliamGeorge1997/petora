<?php

namespace Modules\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\Models\Follow;
use Modules\Community\Services\FollowService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-follow', only: ['index'])]
#[Middleware('permission:Delete-follow', only: ['destroy'])]
class FollowController extends Controller
{
    public function __construct(private FollowService $followService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $follows = $this->followService->findAll($data, ['follower', 'following']);
        if ($request->ajax()) {
            return success(true, __('community::general.follow.fetched'), $follows->items());
        }

        return view('community::follows.index', compact('follows'));
    }

    public function destroy(Follow $follow)
    {
        $this->followService->delete($follow);

        return success(true, __('community::general.follow.deleted'));
    }
}
