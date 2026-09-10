<?php

namespace Modules\Store\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Store\Http\Requests\StoreRequest;

readonly class StoreDto
{
    public function __construct(
        public int $companyId,
        public array $title,
        public ?bool $isActive = null,
        public ?string $phone = null,
        public ?array $address = null,
        public ?array $description = null,
        public ?UploadedFile $image = null,
        public ?int $countryId = null,
        public ?int $cityId = null,
        public ?int $zoneId = null,
        public ?string $latitude = null,
        public ?string $longitude = null,
        public ?array $workingHours = null,
    ) {}

    public static function fromRequest(StoreRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            description: ($request->validated('description_en') || $request->validated('description_ar')) ? [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ] : null,
            address: ($request->validated('address_en') || $request->validated('address_ar')) ? [
                'en' => $request->validated('address_en'),
                'ar' => $request->validated('address_ar'),
            ] : null,
            companyId: $request->validated('company_id'),
            phone: $request->validated('phone'),
            image: $request->file('image'),
            countryId: $request->validated('country_id'),
            cityId: $request->validated('city_id'),
            zoneId: $request->validated('zone_id'),
            latitude: $request->validated('latitude'),
            longitude: $request->validated('longitude'),
            isActive: $request->boolean('is_active'),
            workingHours: $request->validated('working_hours'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'company_id' => $this->companyId,
            'title' => $this->title,
            'phone' => $this->phone,
            'address' => $this->address,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'image' => $this->image,
            'country_id' => $this->countryId,
            'city_id' => $this->cityId,
            'zone_id' => $this->zoneId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
