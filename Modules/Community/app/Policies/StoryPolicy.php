<?php

namespace Modules\Community\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Client\Models\Client;
use Modules\Community\Models\Story;

class StoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-story');
        }

        return true;
    }

    public function view(Authenticatable $user, Story $story): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-story');
        }

        if ($user instanceof Client) {
            return true;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Create-story');
        }

        if ($user instanceof Client) {
            return true;
        }

        return false;
    }

    public function delete(Authenticatable $user, Story $story): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Delete-story');
        }

        if ($user instanceof Client) {
            return $story->client_id === $user->id;
        }

        return false;
    }
}
