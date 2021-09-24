<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Competition;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionPolicy
{
    use HandlesAuthorization;

    public function create(User $user) {
        return $user->isAdmin;
    }

    public function update(User $user, Competition $competition) {
        return $user->isAdmin;
    }

    public function delete(User $user, Competition $competition) {
        return $user->isAdmin;
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
