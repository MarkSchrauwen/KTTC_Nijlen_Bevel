<?php

namespace App\Http\Livewire\Admin;

use App\Models\Competition;
use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Dashboard extends Component
{
    use WithPagination;

    public function mount() {
        $this->firstname = auth()->user()->firstname;
        $this->notifications = auth()->user()->unreadNotifications;
    }

    public function getMembers() {
        return Member::orderBy("lastname","ASC")->get();
    }

    /**
    * The function getNext3Competitions to get 3 earliest future events
    *
    * @return void
    */

    public function getNext10Competitions() {
        $next10Competitions = Competition::where('competition_date', '>=', Carbon::now()->startOfDay())
            ->orderBy('competition_date', 'ASC')
            ->with('members')
            ->limit(10)
            ->get();
        if($next10Competitions != null) {
            foreach($next10Competitions as $competition) {
                if($competition->members != null) {
                    $this->participants = [];
                    foreach($competition->members as $participant) {
                        array_push($this->participants, $participant->id);
                    }
                    $competition->participants = $this->participants;                    
                }           
            }            
        }

        return $next10Competitions;
    }

    /**
    * The function getTotalAdminNotifications totals all unread notifications
    * for admin management purposes
    *
    * @return void
    */

    public function getTotalAdminNotifications() {
        $UnreadNotifications = auth()->user()->unreadNotifications;
        if ($UnreadNotifications != null) {
            return auth()->user()->unreadNotifications
                ->where('type', '!=', "App\Notifications\CreateCompetitionNotification")
                ->where('type', '!=', "App\Notifications\UpdateCompetitionNotification")
                ->where('type', '!=', "App\Notifications\DeleteCompetitionNotification");                    
        }
    }

    /**
    * The function getTotalCompetitionNotifications totals all unread notifications
    * for competition admin purposes
    *
    * @return void
    */

    public function getTotalCompetitionNotifications() {
        $UnreadNotifications = auth()->user()->unreadNotifications;
        if ($UnreadNotifications != null) {
            return auth()->user()->unreadNotifications
                ->whereIn('type',["App\Notifications\CreateCompetitionNotification",
                        "App\Notifications\UpdateCompetitionNotification", 
                        "App\Notifications\DeleteCompetitionNotification"]);                   
        }
    }

    public function render()
    {
        $adminNotifications = $this->getTotalAdminNotifications();
        if ($adminNotifications != null) {
            $numberAdminNotifications = count($adminNotifications);
        }
        $competitionNotifications = $this->getTotalCompetitionNotifications();
        if ($competitionNotifications != null) {
            $numberCompetitionNotifications = count($competitionNotifications);
        }
        return view('livewire.admin.dashboard',[
            "next10Competitions" => $this->getNext10Competitions(),
            "all_members" => $this->getMembers(),
            "adminNotifications" => $adminNotifications,
            "numberAdminNotifications" => $numberAdminNotifications,
            "competitionNotifications" => $competitionNotifications,
            "numberCompetitionNotifications" => $numberCompetitionNotifications,
        ]);
    }
}
