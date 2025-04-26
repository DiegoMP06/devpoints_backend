<?php

namespace App\Policies;

use App\Models\Contest;
use App\Models\ContestUser;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Auth\Access\Response;

class ContestUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContestUser $contestUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Contest $contest): bool
    {
        return $contest->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContestUser $contestUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contest $contest, ContestUser $contestUser): bool
    {
        return $user->id === $contest->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContestUser $contestUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContestUser $contestUser): bool
    {
        return false;
    }
}
