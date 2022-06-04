<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserOrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserOrganization  $userOrganization
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserOrganization $userOrganization)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserOrganization  $userOrganization
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserOrganization $userOrganization)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserOrganization  $userOrganization
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserOrganization $userOrganization)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserOrganization  $userOrganization
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserOrganization $userOrganization)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserOrganization  $userOrganization
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserOrganization $userOrganization)
    {
        //
    }
}
