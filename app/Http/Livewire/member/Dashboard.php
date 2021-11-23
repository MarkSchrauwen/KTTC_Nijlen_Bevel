<?php

namespace App\Http\Livewire\Member;

use App\Models\Competition;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function mount() {
        $this->firstname = auth()->user()->firstname;
    }

        /**
    * The function getNext3Competitions to get 3 earliest future events
    *
    * @return void
    */

    /**
    * The function getNext3Competitions to get 3 earliest future events
    *
    * @return void
    */

    public function getNext3Competitions() {
        $next3Competitions = Competition::where('competition_date', '>=', Carbon::now()->startOfDay())
            ->orderBy('competition_date', 'ASC')
            ->limit(3)
            ->get();

        return $next3Competitions;
    }

    /**
    * The function getOwnNext3Competitions to get 3 earliest future events
    *
    * @return void
    */

    public function getOwnNext3Competitions() {
        $ownNext3Competitions = Competition::where('competition_date', '>=', Carbon::now()->startOfDay())
            ->orderBy('competition_date', 'ASC')        
            ->with('members')
            ->whereHas('members', function($member) {
                $member->where("user_id", auth()->user()->id);
            })
            ->limit(3)
            ->get();

        return $ownNext3Competitions;
    }

    public function render()
    {
        return view('livewire.member.dashboard',[
            "next3Competitions" => $this->getNext3Competitions(),
            "ownNext3Competitions" => $this->getOwnNext3Competitions(),
        ]);
    }
}
