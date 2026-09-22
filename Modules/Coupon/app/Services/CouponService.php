<?php

namespace Modules\Coupon\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Coupon\DTOs\CouponDto;
use Modules\Coupon\Models\Coupon;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Modules\Order\Models\Order;

class CouponService
{
    private string $model = Coupon::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Coupon
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function findByCode(string $code, array $relations = []): null|Coupon
    {
        return $this->model::with($relations)->where('code', $code)->first();
    }

    protected function resolveModel(int|Coupon $couponOrId): Coupon
    {
        return $couponOrId instanceof Coupon ? $couponOrId : $this->findById($couponOrId);
    }

    public function save(CouponDto $dto): Coupon
    {
        return $this->model::create($dto->toArray());
    }

    public function update(int|Coupon $couponOrId, CouponDto $dto): Coupon
    {
        $coupon = $this->resolveModel($couponOrId);
        $coupon->update($dto->toArray());

        return $coupon;
    }

    public function delete(int|Coupon $couponOrId): bool
    {
        $coupon = $this->resolveModel($couponOrId);
        return $coupon->delete();
    }

    public function activate(int|Coupon $couponOrId): Coupon
    {
        $coupon = $this->resolveModel($couponOrId);
        $coupon->update(['is_active' => !$coupon->is_active]);

        return $coupon;
    }



    public function checkCoupon(?string $code, int $client_id): ?Coupon
    {
        if (empty($code)) {
            return null;
        }

        $coupon = $this->findByCode($code);


        if(!$coupon){
            throw ValidationException::withMessages([
                'coupon' => __('coupon::message.not_found'),
            ]);
        }

        if ($coupon->counter >= $coupon->num_of_uses) {
            throw ValidationException::withMessages([
                'coupon' => __('coupon::message.ended'),
            ]);
        }

        if (Order::whereClientId($client_id)->whereCouponId($coupon->id)->count() >= $coupon->client_uses) {
            throw ValidationException::withMessages([
                'coupon' => __('coupon::message.client_limit'),
            ]);
        }

        if ($coupon->date_from && $coupon->date_to) {
            $today = Carbon::today()->toDateString();
            if (! ($coupon->date_from <= $today && $coupon->date_to >= $today)) {
                throw ValidationException::withMessages([
                    'coupon' => __('coupon::message.invalid_date'),
                ]);
            }
        }

        if ($coupon->time_from && $coupon->time_to) {
            $now = Carbon::now()->toTimeString();
            if (! ($coupon->time_from <= $now && $coupon->time_to >= $now)) {
                throw ValidationException::withMessages([
                    'coupon' => __('coupon::message.invalid_time'),
                ]);
            }
        }

        return $coupon;
    }
}