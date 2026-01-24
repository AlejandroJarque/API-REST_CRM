<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    
    public function viewAny(User $user): bool
    {
        return false;
    }

    
    public function view(User $user, Client $client): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $client->user_id === $user->id;
    }


    public function create(User $user): bool
    {
        return false;
    }

    
    public function update(User $user, Client $client): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $client->user_id === $user->id;
    }

    
    public function delete(User $user, Client $client): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $client->user_id === $user->id;
    }

    
    public function restore(User $user, Client $client): bool
    {
        return false;
    }

    
    public function forceDelete(User $user, Client $client): bool
    {
        return false;
    }
}
