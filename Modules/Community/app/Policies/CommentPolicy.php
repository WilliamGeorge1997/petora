<?php

namespace Modules\Community\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Client\Models\Client;
use Modules\Community\Models\Comment;

class CommentPolicy
{
    use HandlesAuthorization;

    public function __construct() {}

    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-comment');
        }

        return false;
    }

    public function view(Authenticatable $user, Comment $comment): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-comment');
        }

        if ($user instanceof Client) {
            return $comment->client_id === $user->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Create-comment');
        }

        if ($user instanceof Client) {
            return true;
        }

        return false;
    }

    public function update(Authenticatable $user, Comment $comment): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Edit-comment');
        }

        if ($user instanceof Client) {
            return $comment->client_id === $user->id;
        }

        return false;
    }

    public function delete(Authenticatable $user, Comment $comment): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Delete-comment');
        }

        if ($user instanceof Client) {
            return $comment->client_id === $user->id;
        }

        return false;
    }
}
