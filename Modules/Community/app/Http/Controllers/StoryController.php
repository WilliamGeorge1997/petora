<?php

namespace Modules\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\Models\Story;
use Modules\Community\Services\StoryService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-story', only: ['index'])]
#[Middleware('permission:Edit-story', only: ['activate'])]
#[Middleware('permission:Delete-story', only: ['destroy'])]
class StoryController extends Controller
{
    public function __construct(private StoryService $storyService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $stories = $this->storyService->findAll($data, ['client']);
        if ($request->ajax()) {
            return success(true, __('community::general.story.fetched'), $stories->items());
        }

        return view('community::stories.index', compact('stories'));
    }

    public function show(Story $story): View
    {
        $story->load('client');

        return view('community::stories.show', compact('story'));
    }

    public function destroy(Story $story)
    {
        $this->storyService->delete($story);

        return success(true, __('community::general.story.deleted'));
    }

    public function activate(Story $story)
    {
        $story = $this->storyService->activate($story);

        return success(
            true,
            $story->is_active ? __('community::general.story.activated') : __('community::general.story.deactivated'),
            $story
        );
    }
}
