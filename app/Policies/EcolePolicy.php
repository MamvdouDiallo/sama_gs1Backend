<?php

namespace App\Policies;

use App\Models\Ecole;
use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EcolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->role->libelle == "Admin";
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ecole $ecole)
    {
        return $user->role == "Admin";
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == "Admin";

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ecole $ecole)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ecole $ecole)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ecole $ecole)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ecole $ecole)
    {
        //
    }
}
