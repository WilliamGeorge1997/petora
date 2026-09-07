<?php

namespace Modules\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Community\Models\Block;
use Modules\Community\Services\BlockService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-block', only: ['index'])]
#[Middleware('permission:Delete-block', only: ['destroy'])]
class BlockController extends Controller
{
    public function __construct(private BlockService $blockService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $blocks = $this->blockService->findAll($data, ['blocker', 'blocked']);
        if ($request->ajax()) {
            return success(true, __('community::general.block.fetched'), $blocks->items());
        }
        return view('community::blocks.index', compact('blocks'));
    }

    public function destroy(Block $block)
    {
        $this->blockService->delete($block);
        return success(true, __('community::general.block.deleted'));
    }
}
