<?php

namespace Modules\Clinic\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Clinic\Http\Requests\ClinicRequest;

readonly class ClinicDto
{
    public function __construct(
        public array $title,
        public bool $isActive,
        public ?string $phone = null,
        public ?array $address = null,
        public ?array $description = null,
        public ?UploadedFile $image = null,
        public ?int $countryId = null,
        public ?int $cityId = null,
        public ?int $zoneId = null,
        public ?string $latitude = null,
        public ?string $longitude = null,
    ) {}

    public static function fromRequest(ClinicRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            isActive: $request->boolean('is_active'),
            phone: $request->validated('phone'),
            address: ($request->validated('address_en') || $request->validated('address_ar')) ? [
                'en' => $request->validated('address_en'),
                'ar' => $request->validated('address_ar'),
            ] : null,
            description: ($request->validated('description_en') || $request->validated('description_ar')) ? [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ] : null,
            image: $request->hasFile('image') ? $request->file('image') : null,
            countryId: $request->validated('country_id'),
            cityId: $request->validated('city_id'),
            zoneId: $request->validated('zone_id'),
            latitude: $request->validated('latitude'),
            longitude: $request->validated('longitude'),
        );
    }

    public function toArray(): array
    {
        $data = [
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
