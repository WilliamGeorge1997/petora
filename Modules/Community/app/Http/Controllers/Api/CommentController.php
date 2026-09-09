<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Community\DTOs\CommentDto;
use Modules\Community\Http\Requests\CommentRequest;
use Modules\Community\Http\Requests\ReplyRequest;
use Modules\Community\Models\Comment;
use Modules\Community\Models\Post;
use Modules\Community\Services\CommentService;
use Modules\Community\Transformers\CommentResource;

#[Middleware('auth:client', except: ['index', 'replies'])]
class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    public function index(Request $request, Post $post)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'post_id' => $post->id, 'parent_id' => null])->all();
        $comments = $this->commentService->active($data, ['client'], ['replies' => fn($q) => $q->active()]);
        return success(true, __('community::message.comment.fetched'), paginatedResource($comments, CommentResource::class));
    }

    public function replies(Request $request, Comment $comment)
    {
        $data = $request->merge(['pagination_type' => 'cursor', 'parent_id' => $comment->id])->all();
        $replies = $this->commentService->active($data, ['client']);
        return success(true, __('community::message.comment.fetched'), paginatedResource($replies, CommentResource::class));
    }

    public function store(CommentRequest $request, Post $post)
    {
        $data = CommentDto::fromRequest($request);
        $comment = $this->commentService->save($post, $data);
        return success(true, __('community::message.comment.created'), $comment);
    }

    public function reply(ReplyRequest $request, Comment $comment)
    {
        $data = CommentDto::fromRequest($request);
        $reply = $this->commentService->reply($comment, $data);
        return success(true, __('community::message.comment.created'), $reply);
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
