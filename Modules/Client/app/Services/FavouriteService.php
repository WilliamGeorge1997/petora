<?php

namespace Modules\Client\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\CursorPaginator;
use Modules\Client\DTOs\FavouriteDto;
use Modules\Client\Models\Favourite;
use Modules\Product\Services\SellerProductService;

class FavouriteService
{
    private string $model = Favourite::class;

    public function findAll(array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Favourite
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Favourite $favouriteOrId): Favourite
    {
        return $favouriteOrId instanceof Favourite ? $favouriteOrId : $this->findById($favouriteOrId);
    }

    public function findBy(array|string $column, mixed $value = null, array $data = [], array $relations = []): mixed
    {
        $query = $this->model::query()->with($relations);

        if (is_array($column)) {
            $query->where($column);
        } else {
            $query->where($column, $value);
        }

        if (! empty($data)) {
            return getCaseCollection($query->latest('id'), $data);
        }

        return $query->first();
    }

    public function clientFavourites(int $clientId, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()
            ->with($relations)
            ->where('client_id', $clientId)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function save(FavouriteDto $dto): Favourite
    {
        /** @var Favourite $favourite */
        $favourite = $this->model::create($dto->toArray());

        return $favourite;
    }

    public function delete(int|Favourite $favouriteOrId): bool
    {
        $favourite = $this->resolveModel($favouriteOrId);

        return (bool) $favourite->delete();
    }

    public function toggle(FavouriteDto $dto): array
    {
        $sellerProduct = app(SellerProductService::class)->findBySellerAndProduct(
            $dto->productId,
            $dto->storeId,
            $dto->clinicId
        );

        if (! $sellerProduct) {
            throw new ModelNotFoundException(__('product::message.not_found'));
        }

        $existing = $this->model::where('client_id', $dto->clientId)
            ->where('seller_product_id', $sellerProduct->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return [
                'action' => 'removed',
                'message' => __('client::message.favourite.removed'),
                'favourite' => null,
            ];
        }

        $data = $dto->toArray();
        $data['seller_product_id'] = $sellerProduct->id;
        
        $favourite = $this->model::create($data);


        return [
            'action' => 'added',
            'message' => __('client::message.favourite.added'),
            'favourite' => $favourite,
        ];
    }
}
