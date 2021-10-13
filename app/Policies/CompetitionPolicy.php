<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Competition;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionPolicy
{
    use HandlesAuthorization;

    public function create(User $user) {
        return in_array($user->role_id,[Role::isSiteAdmin]);
    }

    public function update(User $user) {
        return in_array($user->role_id,[Role::isSiteAdmin,Role::isContentManager,Role::isBlogModerator,Role::isTeamCaptain]);
    }

    public function delete(User $user, Competition $competition) {
        return in_Array($user->role_id,[Role::isSiteAdmin]);
    }

    public function view(User $user) {
        return in_array($user->role_id,[Role::isSiteAdmin,Role::isContentManager,Role::isBlogModerator,Role::isTeamCaptain,Role::isMember]);
    }

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
