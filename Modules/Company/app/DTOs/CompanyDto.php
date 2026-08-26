<?php

namespace Modules\Company\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Company\Http\Requests\CompanyRequest;

readonly class CompanyDto
{
    public function __construct(
        public array $title,
        public bool $isActive,
        public ?string $phone = null,
        public ?array $address = null,
        public ?array $description = null,
        public ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(CompanyRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->input('title_en'),
                'ar' => $request->input('title_ar'),
            ],
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
            isActive: $request->boolean('is_active'),
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
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
