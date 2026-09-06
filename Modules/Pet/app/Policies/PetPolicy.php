<?php

namespace Modules\Pet\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Client\Models\Client;
use Modules\Pet\Models\Pet;

class PetPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-pet');
        }

        return false;
    }

    public function view(Authenticatable $user, Pet $pet): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-pet');
        }
        
        if ($user instanceof Client) {
            return $pet->client_id === $user->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Create-pet');
        }

        if ($user instanceof Client) {
            return true;
        }

        return false;
    }

    public function update(Authenticatable $user, Pet $pet): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Edit-pet');
        }

        if ($user instanceof Client) {
            return $pet->client_id === $user->id;
        }

        return false;
    }

    public function delete(Authenticatable $user, Pet $pet): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Delete-pet');
        }

        if ($user instanceof Client) {
            return $pet->client_id === $user->id;
        }

        return false;
    }
}
