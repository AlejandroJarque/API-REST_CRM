<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityPolicy
{
    
    public function viewAny(User $user): bool
    {
        return false;
    }

    
    public function view(User $user, Activity $activity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $activity->client->user_id === $user->id;
    }

    
    public function create(User $user): bool
    {
        return false;
    }

    
    public function update(User $user, Activity $activity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $activity->client->user_id === $user->id;
    }

    
    public function delete(User $user, Activity $activity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $activity->client->user_id === $user->id;
    }

    
    public function restore(User $user, Activity $activity): bool
    {
        return false;
    }

    
    public function forceDelete(User $user, Activity $activity): bool
    {
        return false;
    }
}
