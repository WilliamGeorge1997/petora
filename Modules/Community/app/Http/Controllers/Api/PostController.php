<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\PostDto;
use Modules\Community\Http\Requests\PostRequest;
use Modules\Community\Models\Post;
use Modules\Community\Services\PostService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

#[Middleware('auth:client')]
class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $posts = $this->postService->active($data, ['client', 'media', 'hashtags', 'comments']);
        return success(true, __('community::message.post.fetched'), $posts);
    }

    public function show(int $post_id)
    {
        $post = $this->postService->findById($post_id, ['client', 'media', 'hashtags', 'comments']);
        return success(true, __('community::message.post.fetched'), $post);
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
