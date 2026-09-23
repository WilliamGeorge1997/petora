<?php

namespace Modules\Client\DTOs;

use Modules\Client\Http\Requests\FavouriteRequest;

readonly class FavouriteDto
{
    public function __construct(
        public int $clientId,
        public int $productId,
        public ?int $storeId = null,
        public ?int $clinicId = null,
    ) {}

    public static function fromRequest(FavouriteRequest $request): self
    {
        return new self(
            clientId: (int) auth('client')->id(),
            productId: (int) $request->validated('product_id'),
            storeId: $request->validated('store_id'),
            clinicId: $request->validated('clinic_id'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'product_id' => $this->productId,
            'store_id' => $this->storeId,
            'clinic_id' => $this->clinicId,
        ];
        return array_filter($data, fn($value) => ! is_null($value));
    }
}
