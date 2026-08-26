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
        public ?string $lat = null,
        public ?string $long = null,
    ) {}

    public static function fromRequest(ClinicRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->input('title_en'),
                'ar' => $request->input('title_ar'),
            ],
            isActive: $request->boolean('is_active'),
            phone: $request->input('phone'),
            address: ($request->input('address_en') || $request->input('address_ar')) ? [
                'en' => $request->input('address_en'),
                'ar' => $request->input('address_ar'),
            ] : null,
            description: ($request->input('description_en') || $request->input('description_ar')) ? [
                'en' => $request->input('description_en'),
                'ar' => $request->input('description_ar'),
            ] : null,
            image: $request->hasFile('image') ? $request->file('image') : null,
            lat: $request->input('lat'),
            long: $request->input('long'),
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
            'lat' => $this->lat,
            'long' => $this->long,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
