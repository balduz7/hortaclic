<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlacePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 3]);
    }

    public function view(User $user, Place $place): bool
    {
        return in_array($user->role_id, [1, 2, 3]);
    }

    public function create(User $user): bool
    {
        return $user->role_id === 1;
    }

    public function update(User $user, Place $place): bool
    {
        if ($user->role_id === 1 && $user->id === $place->author_id) {
            return $user->role_id;
        } elseif ($user->role_id === 2) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Place $place): bool
    {
        return $user->role_id === 1 && $user->id === $place->author_id;
    }

    public function restore(User $user, Place $place): bool
    {
        return $user->role_id === 3;
    }

    public function forceDelete(User $user, Place $place): bool
    {
        return $user->role_id === 3;
    }

}