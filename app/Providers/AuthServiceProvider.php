<?php

namespace App\Providers;

use App\Models\BoardMember;
use App\Models\Team;
use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\Member;
use App\Models\User;
use App\Policies\BoardMemberPolicy;
use App\Policies\CompetitionPolicy;
use App\Policies\CompetitionTeamPolicy;
use App\Policies\MemberPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        Competition::class => CompetitionPolicy::class,
        BoardMember::class => BoardMemberPolicy::class,
        CompetitionTeam::class => CompetitionTeamPolicy::class,
        Member::class => MemberPolicy::class,
        User::class => UserPolicy::class,
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
