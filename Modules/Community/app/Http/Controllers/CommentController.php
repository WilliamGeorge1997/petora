<?php

namespace Modules\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\Models\Comment;
use Modules\Community\Services\CommentService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-comment', only: ['index'])]
#[Middleware('permission:Edit-comment', only: ['activate'])]
#[Middleware('permission:Delete-comment', only: ['destroy'])]
class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $comments = $this->commentService->findAll($data, ['client', 'post']);
        if ($request->ajax()) {
            return success(true, __('community::general.comment.fetched'), $comments->items());
        }

        return view('community::comments.index', compact('comments'));
    }

    public function show(Comment $comment): View
    {
        $comment->load(['client', 'post', 'parent', 'replies']);

        return view('community::comments.show', compact('comment'));
    }

    public function destroy(Comment $comment)
    {
        $this->commentService->delete($comment);

        return success(true, __('community::general.comment.deleted'));
    }

    public function activate(Comment $comment)
    {
        $comment = $this->commentService->activate($comment);

        return success(
            true,
            $comment->is_active ? __('community::general.comment.activated') : __('community::general.comment.deactivated'),
            $comment
        );
    }
}
