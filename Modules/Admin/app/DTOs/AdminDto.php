<?php

namespace Modules\Admin\DTOs;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class AdminDto
{
    public function __construct(
        public string $name,
        public string $email,
        public int $is_active,
        public ?string $password = null,
        public ?string $phone = null,
        public UploadedFile|string|null $image = null,
        public ?int $role = null,
        public ?int $company_id = null,
        public ?int $store_id = null,
        public ?int $clinic_id = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: (string) $request->input('name'),
            email: (string) $request->input('email'),
            is_active: $request->has('is_active') ? 1 : 0,
            password: (string) $request->filled('password') ? Hash::make($request->input('password')) : null,
            phone: (string) $request->input('phone'),
            image: $request->hasFile('image') ? $request->file('image') : null,
            role: (int) $request->input('role'),
            company_id: $request->filled('company_id') ? (int) $request->input('company_id') : null,
            store_id: $request->filled('store_id') ? (int) $request->input('store_id') : null,
            clinic_id: $request->filled('clinic_id') ? (int) $request->input('clinic_id') : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'role' => $this->role,
            'company_id' => $this->company_id,
            'store_id' => $this->store_id,
            'clinic_id' => $this->clinic_id,
        ], fn($value) => $value !== null);
    }
}
