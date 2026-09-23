<?php

namespace Modules\Client\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Client\DTOs\FavouriteDto;
use Modules\Client\Http\Requests\FavouriteRequest;
use Modules\Client\Services\FavouriteService;
use Modules\Client\Transformers\FavouriteResource;

#[Middleware('auth:client')]
class FavouriteController extends Controller
{
    public function __construct(private FavouriteService $favouriteService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $request->merge([
            'pagination_type' => 'cursor',
            'paginated' => $request->input('paginted') ?: 50
        ])->all();

        $relations = [
            'sellerProduct.product.images',
            'sellerProduct.images',
            'sellerProduct.clinic',
            'sellerProduct.store',
        ];
        $favourites = $this->favouriteService->clientFavourites((int) auth('client')->id(), $data, $relations);
        return success(true, __('client::message.favourite.fetched'), paginatedResource($favourites, FavouriteResource::class));
    }

    public function toggle(FavouriteRequest $request): JsonResponse
    {
        $dto = FavouriteDto::fromRequest($request);
        $result = $this->favouriteService->toggle($dto);
        return success(true, $result['message']);
    }
}
