<?php

namespace Modules\Doctor\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Doctor\Http\Requests\DoctorRequest;

readonly class DoctorDto
{
    public function __construct(
        public int $clinicId,
        public array $name,
        public array $specialty,
        public ?bool $isActive = null,
        public ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(DoctorRequest $request): self
    {
        return new self(
            clinicId: $request->validated('clinic_id'),
            name: [
                'en' => $request->validated('name_en'),
                'ar' => $request->validated('name_ar'),
            ],
            specialty: [
                'en' => $request->validated('specialty_en'),
                'ar' => $request->validated('specialty_ar'),
            ],
            image: $request->file('image'),
            isActive: $request->boolean('is_active'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'clinic_id' => $this->clinicId,
            'name'      => $this->name,
            'specialty' => $this->specialty,
            'is_active' => $this->isActive,
            'image'     => $this->image,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
