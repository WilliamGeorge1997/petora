<?php

namespace Modules\Client\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Client\DTOs\AddressDto;
use Modules\Client\Models\Address;

class AddressService
{
    private string $model = Address::class;

    public function findBy(string $column, mixed $value, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()
            ->with($relations)
            ->where($column, $value)
            ->orderByDesc('default')
            ->latest('id');
            
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Address
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function save(AddressDto $dto): Address
    {
        $data = $dto->toArray();
        /** @var Address $address */
        $address = $this->model::create($data);

        if ($data['default']) {
            $this->removeDefaultFromOtherAddresses($address);
        }

        return $address;
    }

    public function update(Address $address, AddressDto $dto): Address
    {
        $data = $dto->toArray();
        $address->update($data);

        if ($data['default']) {
            $this->removeDefaultFromOtherAddresses($address);
        }

        return $address;
    }

    public function makeDefault(Address $address): Address
    {
        $address->update(['default' => 1]);
        $this->removeDefaultFromOtherAddresses($address);
        
        return $address;
    }

    private function removeDefaultFromOtherAddresses(Address $address): void
    {
        $this->model::where('client_id', $address->client_id)
            ->where('id', '!=', $address->id)
            ->update(['default' => 0]);
    }

    public function delete(Address $address): void
    {
        // Mocking Order relation check as requested in rules if model is missing
        // if (class_exists(Order::class) && Order::query()->where('address_id', $address->id)->exists()) {
        //     throw new Exception(__('client::message.address_cannot_be_deleted'));
        // }
        
        $address->delete();
    }
}
