<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\StoryDto;
use Modules\Community\Http\Requests\StoryRequest;
use Modules\Community\Models\Story;
use Modules\Community\Services\StoryService;
use Illuminate\Support\Facades\Gate;

#[Middleware('auth:client')]
class StoryController extends Controller
{
    /**
     * @var StoryService
     */
    private StoryService $storyService;

    public function __construct(StoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $stories = $this->storyService->active($data, ['client']);
        return success(true, __('community::message.story.fetched'), $stories);
    }

    public function store(StoryRequest $request)
    {
        $data = StoryDto::fromRequest($request);
        $story = $this->storyService->save($data);
        return success(true, __('community::message.story.created'), $story);
    }

    public function destroy(Story $story)
    {
        Gate::authorize('delete', $story);
        $this->storyService->delete($story);
        return success(true, __('community::message.story.deleted'));
    }
}
