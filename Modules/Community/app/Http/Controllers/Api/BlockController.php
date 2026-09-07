<?php

namespace Modules\Community\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Community\DTOs\BlockDto;
use Modules\Community\Http\Requests\BlockRequest;
use Modules\Community\Models\Block;
use Modules\Community\Services\BlockService;
use Illuminate\Support\Facades\Gate;

#[Middleware('auth:client')]
class BlockController extends Controller
{
    public function __construct(private BlockService $blockService) {}

    public function index(Request $request)
    {
        $blocker_id = auth('client')->id();
        $data = $request->merge(['pagination_type' => 'cursor', 'blocker_id' => $blocker_id])->all();
        $blocks = $this->blockService->findAll($data, ['blocked']);
        return success(true, __('community::message.block.fetched'), $blocks);
    }

    public function toggle(Request $request, int $user_id)
    {
        $blocker_id = auth('client')->id();
        $block = $this->blockService->toggleBlock($blocker_id, $user_id);
        $message = $block ? __('community::message.block.created') : __('community::message.block.deleted');
        return success(true, $message, $block);
    }
}
