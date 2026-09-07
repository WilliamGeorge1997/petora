<?php

namespace Modules\Community\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Client\Models\Client;
use Modules\Community\Models\Post;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-post');
        }

        return false;
    }

    public function view(Authenticatable $user, Post $post): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-post');
        }
        
        if ($user instanceof Client) {
            return $post->client_id === $user->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Create-post');
        }

        if ($user instanceof Client) {
            return true;
        }

        return false;
    }

    public function update(Authenticatable $user, Post $post): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Edit-post');
        }

        if ($user instanceof Client) {
            return $post->client_id === $user->id;
        }

        return false;
    }

    public function delete(Authenticatable $user, Post $post): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Delete-post');
        }

        if ($user instanceof Client) {
            return $post->client_id === $user->id;
        }

        return false;
    }
}
