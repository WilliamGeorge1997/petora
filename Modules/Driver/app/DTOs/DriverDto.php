<?php

namespace Modules\Driver\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Driver\Http\Requests\DriverRequest;

readonly class DriverDto
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $password = null,
        public ?string $licenseId = null,
        public ?UploadedFile $image = null,
        public ?int $storeId = null,
        public ?int $clinicId = null,
        public ?int $countryId = null,
        public ?int $cityId = null,
        public ?int $zoneId = null,
        public ?string $latitude = null,
        public ?string $longitude = null,
        public ?string $fcmToken = null,
        public ?string $locale = null,
        public ?bool $allowNotification = null,
        public ?bool $isAvailable = null,
        public ?bool $isActive = null,
    ) {}

    public static function fromRequest(DriverRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            phone: $request->validated('phone'),
            password: $request->validated('password'),
            licenseId: $request->validated('license_id'),
            image: $request->file('image'),
            storeId: $request->validated('store_id'),
            clinicId: $request->validated('clinic_id'),
            countryId: $request->validated('country_id'),
            cityId: $request->validated('city_id'),
            zoneId: $request->validated('zone_id'),
            latitude: $request->validated('latitude'),
            longitude: $request->validated('longitude'),
            fcmToken: $request->validated('fcm_token'),
            locale: $request->validated('locale'),
            allowNotification: $request->boolean('allow_notification'),
            isAvailable: $request->boolean('is_available'),
            isActive: $request->boolean('is_active'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'password' => $this->password,
            'license_id' => $this->licenseId,
            'store_id' => $this->storeId,
            'clinic_id' => $this->clinicId,
            'country_id' => $this->countryId,
            'city_id' => $this->cityId,
            'zone_id' => $this->zoneId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'fcm_token' => $this->fcmToken,
            'locale' => $this->locale,
            'allow_notification' => $this->allowNotification,
            'is_available' => $this->isAvailable,
            'is_active' => $this->isActive,
            'image' => $this->image,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        if (is_null($this->password)) {
            unset($data['password']);
        }

        return $data;
    }
}
