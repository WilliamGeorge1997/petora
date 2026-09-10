<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Community\DTOs\PostDto;
use Modules\Community\Http\Requests\PostRequest;
use Modules\Community\Models\Post;
use Modules\Community\Services\PostService;
use Modules\Community\Transformers\PostResource;

#[Middleware('auth:client', except: ['index', 'show'])]
class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $posts = $this->postService->active(
            $data,
            $this->postService->postRelations(includeComments: true, commentsLimit: 2, repliesLimit: 2),
            $this->postService->postCounts()
        );

        return success(true, __('community::message.post.fetched'), paginatedResource($posts, PostResource::class));
    }

    public function show(int $post_id)
    {
        $post = $this->postService->findById(
            $post_id,
            $this->postService->postRelations(includeComments: false),
            $this->postService->postCounts()
        );

        return success(true, __('community::message.post.fetched'), new PostResource($post));
    }

    public function store(PostRequest $request)
    {
        $data = PostDto::fromRequest($request);
        $post = $this->postService->save($data);

        return success(true, __('community::message.post.created'), $post);
    }

    public function update(PostRequest $request, Post $post)
    {
        Gate::authorize('update', $post);
        $data = PostDto::fromRequest($request);
        $post = $this->postService->update($post, $data);

        return success(true, __('community::message.post.updated'), $post);
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $this->postService->delete($post);

        return success(true, __('community::message.post.deleted'));
    }
}
