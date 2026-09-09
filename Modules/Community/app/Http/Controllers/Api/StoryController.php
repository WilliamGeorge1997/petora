<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\StoryDto;
use Modules\Community\Http\Requests\StoryRequest;
use Modules\Community\Models\Story;
use Modules\Community\Services\StoryService;
use Modules\Community\Transformers\StoryResource;
use Modules\Community\Transformers\StoryFeedResource;
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
        $clients = $this->storyService->feed($data);
        return success(true, __('community::message.story.fetched'), paginatedResource($clients, StoryFeedResource::class));
    }

    public function clientStories(int $client_id, Request $request)
    {
        $data = $request->merge([
            'pagination_type' => 'cursor',
            'client_id' => $client_id,
        ])->all();
        $stories = $this->storyService->active($data, counts: $this->storyService->storyCounts());
        return success(true, __('community::message.story.fetched'), paginatedResource($stories, StoryResource::class));
    }

    public function show(int $story_id)
    {
        $story = $this->storyService->findById($story_id, ['client'], $this->storyService->storyCounts());
        return success(true, __('community::message.story.fetched'), new StoryResource($story));
    }

    public function store(StoryRequest $request)
    {
        $data = StoryDto::fromRequest($request);
        $story = $this->storyService->save($data);
        return success(true, __('community::message.story.created'), new StoryResource($story->load('client')));
    }

    public function destroy(Story $story)
    {
        Gate::authorize('delete', $story);
        $this->storyService->delete($story);
        return success(true, __('community::message.story.deleted'));
    }
}
