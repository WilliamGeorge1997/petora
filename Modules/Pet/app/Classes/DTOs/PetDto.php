<?php

namespace Modules\Pet\Classes\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Pet\Http\Requests\PetRequest;

class PetDto
{
    public function __construct(
        public int $clientId,
        public int $petTypeId,
        public array $name,
        public ?array $breed = null,
        public ?string $dateOfBirth = null,
        public ?float $weight = null,
        public ?string $gender = null,
        public ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(PetRequest $request): self
    {
        return new self(
            clientId: $request->validated('client_id'),
            petTypeId: $request->validated('pet_type_id'),
            name: [
                'en' => $request->validated('name_en'),
                'ar' => $request->validated('name_ar'),
            ],
            breed: ($request->validated('breed_en') || $request->validated('breed_ar')) ? [
                'en' => $request->validated('breed_en'),
                'ar' => $request->validated('breed_ar'),
            ] : null,
            dateOfBirth: $request->validated('date_of_birth'),
            weight: $request->validated('weight') ? (float) $request->validated('weight') : null,
            gender: $request->validated('gender'),
            image: $request->hasFile('image') ? $request->file('image') : null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'pet_type_id' => $this->petTypeId,
            'name' => $this->name,
            'breed' => $this->breed,
            'date_of_birth' => $this->dateOfBirth,
            'weight' => $this->weight,
            'gender' => $this->gender,
            'image' => $this->image,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
