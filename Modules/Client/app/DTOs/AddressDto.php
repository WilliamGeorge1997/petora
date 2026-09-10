<?php

namespace Modules\Client\DTOs;

use Modules\Client\Http\Requests\AddressRequest;

readonly class AddressDto
{
    public function __construct(
        public int $clientId,
        public string $title,
        public int $countryId,
        public int $cityId,
        public int $zoneId,
        public ?string $block,
        public ?string $street,
        public ?string $houseNumber,
        public ?string $notes,
        public ?string $latitude,
        public ?string $longitude,
        public bool $isDefault,
    ) {}

    public static function fromRequest(AddressRequest $request): self
    {
        return new self(
            clientId: $request->validated('client_id'),
            title: $request->validated('title'),
            countryId: $request->validated('country_id'),
            cityId: $request->validated('city_id'),
            zoneId: $request->validated('zone_id'),
            block: $request->validated('block'),
            street: $request->validated('street'),
            houseNumber: $request->validated('house_number'),
            notes: $request->validated('notes'),
            latitude: $request->validated('latitude'),
            longitude: $request->validated('longitude'),
            isDefault: (bool) $request->validated('is_default', false),
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'title' => $this->title,
            'country_id' => $this->countryId,
            'city_id' => $this->cityId,
            'zone_id' => $this->zoneId,
            'block' => $this->block,
            'street' => $this->street,
            'house_number' => $this->houseNumber,
            'notes' => $this->notes,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_default' => $this->isDefault,
        ];
    }
}
