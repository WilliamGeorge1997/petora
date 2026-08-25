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
        public ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(CompanyRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->input('title.en'),
                'ar' => $request->input('title.ar'),
            ],
            phone: $request->input('phone'),
            address: ($request->input('address.en') || $request->input('address.ar')) ? [
                'en' => $request->input('address.en'),
                'ar' => $request->input('address.ar'),
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
            'is_active' => $this->isActive,
            'image' => $this->image,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
