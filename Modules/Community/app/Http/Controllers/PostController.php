<?php

namespace Modules\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\Models\Post;
use Modules\Community\Services\PostService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-post', only: ['index'])]
#[Middleware('permission:Edit-post', only: ['activate'])]
#[Middleware('permission:Delete-post', only: ['destroy'])]
class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $posts = $this->postService->findAll($data, ['client', 'pet']);
        if ($request->ajax()) {
            return success(true, __('community::general.post.fetched'), $posts->items());
        }
        return view('community::posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        $post->load(['client', 'pet', 'media', 'hashtags']);
        return view('community::posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);
        return success(true, __('community::general.post.deleted'));
    }

    public function activate(Post $post)
    {
        $post = $this->postService->activate($post);
        return success(
            true,
            $post->is_active ?  __('community::general.post.activated') :  __('community::general.post.deactivated'),
            $post
        );
    }
}
