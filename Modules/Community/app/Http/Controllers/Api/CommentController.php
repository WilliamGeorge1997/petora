<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\CommentDto;
use Modules\Community\Http\Requests\CommentRequest;
use Modules\Community\Models\Comment;
use Modules\Community\Services\CommentService;
use Illuminate\Support\Facades\Gate;

#[Middleware('auth:client')]
class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    public function index(Request $request, int $post_id)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'post_id' => $post_id])->all();
        $comments = $this->commentService->active($data, ['user', 'replies']);
        return success(true, __('community::message.comment.fetched'), $comments);
    }

    public function store(CommentRequest $request, int $post_id)
    {
        $request->merge(['post_id' => $post_id]);
        $data = CommentDto::fromRequest($request);
        $comment = $this->commentService->save($data);
        return success(true, __('community::message.comment.created'), $comment);
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        Gate::authorize('update', $comment);
        $data = CommentDto::fromRequest($request);
        $comment = $this->commentService->update($comment, $data);
        return success(true, __('community::message.comment.updated'), $comment);
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);
        $this->commentService->delete($comment);
        return success(true, __('community::message.comment.deleted'));
    }
}
