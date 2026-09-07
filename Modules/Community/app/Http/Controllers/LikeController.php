<?php

namespace Modules\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Community\Models\Like;
use Modules\Community\Services\LikeService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-like', only: ['index'])]
#[Middleware('permission:Delete-like', only: ['destroy'])]
class LikeController extends Controller
{
    public function __construct(private LikeService $likeService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $likes = $this->likeService->findAll($data, ['client', 'likeable']);
        if ($request->ajax()) {
            return success(true, __('community::general.like.fetched'), $likes->items());
        }
        return view('community::likes.index', compact('likes'));
    }

    public function destroy(Like $like)
    {
        $this->likeService->delete($like);
        return success(true, __('community::general.like.deleted'));
    }
}
