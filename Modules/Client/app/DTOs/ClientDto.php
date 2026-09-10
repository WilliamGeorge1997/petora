<?php

namespace Modules\Client\DTOs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Modules\Client\Http\Requests\ClientRegisterRequest;

readonly class ClientDto
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $password = null,
        public ?string $email = null,
        public ?UploadedFile $image = null,
        public ?string $fcmToken = null,
        public ?string $locale = null,
        public ?string $verifyCode = null,
        public ?bool $isActive = null,
    ) {}

    public static function fromRegisterRequest(ClientRegisterRequest $request): self
    {
        $verifyCode = app()->environment('local') ? '999999' : (string) rand(100000, 999999);

        return self::mapRequestToDto($request, false, $verifyCode);
    }

    public static function fromAdminRequest(FormRequest $request): self
    {
        return self::mapRequestToDto($request, $request->boolean('is_active'));
    }

    public static function fromClientRequest(FormRequest $request): self
    {
        $newPassword = $request->validated('new_password');

        return new self(
            name: $request->validated('name'),
            phone: $request->validated('phone'),
            password: $newPassword ? Hash::make($newPassword) : null,
            email: $request->validated('email'),
            image: $request->file('image'),
            fcmToken: $request->validated('fcm_token'),
            locale: $request->validated('locale'),
            verifyCode: null,
            isActive: null,
        );
    }

    // Helper
    private static function mapRequestToDto(FormRequest $request, ?bool $isActive, ?string $verifyCode = null): self
    {
        return new self(
            name: $request->validated('name'),
            phone: $request->validated('phone'),
            password: $request->validated('password') ? Hash::make($request->validated('password')) : null,
            email: $request->validated('email'),
            image: $request->file('image'),
            fcmToken: $request->validated('fcm_token'),
            locale: $request->validated('locale'),
            verifyCode: $verifyCode,
            isActive: $isActive,
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'password' => $this->password,
            'email' => $this->email,
            'image' => $this->image,
            'fcm_token' => $this->fcmToken,
            'locale' => $this->locale,
            'verify_code' => $this->verifyCode,
            'is_active' => $this->isActive,
        ];

        $nullableFields = ['image', 'locale', 'password', 'is_active'];

        foreach ($nullableFields as $key) {
            if (is_null($data[$key])) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
