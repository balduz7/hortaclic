<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        if (in_array($user->role_id, [1, 2, 3])) {
            return $user->role_id;
        }
        return false;
    }

    public function view(User $user, Post $post): bool
    {
        if (in_array($user->role_id, [1, 2, 3])) {
            return $user->role_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role_id === 3;
    }

    public function update(User $user, Post $post): bool
    {
        return $user->role_id === 3 && $user->id === $post->author_id;
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->role_id === 3 && $user->id === $post->author_id) {
            return $user->role_id;
        } elseif ($user->role_id === 2) {
            return true;
        }
        return false;
    }
    
    public function like(User $user): bool
    {
        return $user->role_id == 3;
    }

    public function restore(User $user, Post $post): bool
    {
    }

    public function forceDelete(User $user, Post $post): bool
    {
    }
}
