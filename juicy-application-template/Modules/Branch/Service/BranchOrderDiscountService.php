<?php

namespace Modules\Branch\Service;

use Modules\Branch\Entities\Branch;

class BranchOrderDiscountService
{
    public function update(Branch $branch, array $data): void
    {
        $branch->settings()->updateOrCreate(
            ['branch_id' => $branch->id],
            ['is_order_discount_enabled' => $data['is_order_discount_enabled'] ?? 0]
        );

        $discounts = collect($data['order_discounts'] ?? []);
        
        $branch->orderDiscounts()->whereNotIn('id', $discounts->pluck('id')->filter())->delete();

        $discounts->each(fn($discount) => $branch->orderDiscounts()->updateOrCreate(
            ['id' => empty($discount['id']) ? null : $discount['id']],
            [
                'min_total' => $discount['min_total'],
                'type'      => $discount['type'],
                'value'     => $discount['value']
            ]
        ));
    }
}
