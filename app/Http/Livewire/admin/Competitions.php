<?php

namespace App\Http\Livewire\Admin;

use App\Mail\CompetitionsMail;
use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\CompetitionOrganisation;
use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use App\Notifications\CreateCompetitionNotification;
use App\Notifications\DeleteCompetitionNotification;
use App\Notifications\UpdateCompetitionNotification;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Competitions extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $modalFormVisible =false;
    public $modalConfirmDeleteVisible = false;
    public $modalSearchVisible = false;
    public $modelId;

    /**
    * Livewire public properties
    */

    public $team_name;
    public $competition;
    public $season;
    public $competition_number;
    public $competition_date;
    public $competition_time;
    public $home_team;
    public $visitor_team;
    public $comment;
    public $participants = [];
    public $sendMail = true;


    /**
    * Other public properties
    */
    public $teamSearch = "";
    public $competitionSearch = "";
    public $seasonSearch = "";
    public $homeSearch = "";
    public $visitorSearch = "";
    public $startDateSearch;
    public $endDateSearch;
    public $participantsSearch;
    public $competition_search_name = "";
    public $team_search_name = "";
    public $start_date = "";
    public $end_date = "";
    public $participants_search;

    /**
    * Validation rules
    *
    * @return void
    */
    public function rules(){
        return [
            "team_name"=> 'required',
            "competition" => 'nullable',
            "season" => 'required',
            "competition_number" => 'required',
            "competition_date" => 'required|date_format:Y-m-d',
            "competition_time" => 'required',
            "home_team" => 'required',
            "visitor_team" => 'required',
            "comment" => 'nullable',
        ];
    }

    public function mount() {
        $this->teamSearch = "";
        $this->competitionSearch = "";
        $this->seasonSearch = "";
        $this->homeSearch = "";
        $this->visitorSearch = "";
        $this->participants = [];
        $this->startDateSearch = Carbon::now()->startOfDay();
    }

    /**
    * Loads the model data of this component.
    *
    * @return void
    */
    public function loadModel(){
        $data = Competition::with('members')->find($this->modelId);
        $this->participants = [];
        // Assign the variables here
        $this->team_name = $data->team_name;
        $this->competition = $data->competition;
        $this->season = $data->season;
        $this->competition_number = $data->competition_number;
        $this->competition_date = $data->competition_date;
        $this->competition_time = $data->competition_time;
        $this->home_team = $data->home_team;
        $this->visitor_team = $data->visitor_team;
        $this->comment = $data->comment;
        foreach($data->members as $participant) {
            array_push($this->participants, $participant->id);
        }
    }

    /**
    * The data for the model mapped in this component
    *
    * @return void
    */
    public function modelData(){
        return [
            "team_name" => $this->team_name,
            "competition" => $this->competition,
            "season" => $this->season,
            "competition_number" => $this->competition_number,
            "competition_date" => $this->competition_date,
            "competition_time" => $this->competition_time,
            "home_team" => $this->home_team,
            "visitor_team" => $this->visitor_team,
            "comment" => $this->comment,
        ];
    }

        public function searchSubmit() {
            $this->authorize('view', Competition::class);
            $this->competitionSearch = $this->competition_search_name;
            $this->teamSearch = $this->team_search_name;
            $this->startDateSearch = $this->start_date;
            $this->endDateSearch = $this->end_date;
            $this->participantsSearch = $this->participants_search;
            $this->modalSearchVisible = false;
            $this->resetPage();
        }

    public function getCompetitionNames() {
        return CompetitionOrganisation::select('name')->get()->unique('name');
    }

    public function getTeamNames() {
        return CompetitionTeam::select('name')->get()->unique('name');
    }

    public function getMembers() {
        return Member::orderBy("lastname","ASC")->get();
    }

    public function resetOnlyLivewireVariables() {
        $competitionSearch = $this->competitionSearch;
        $teamSearch = $this->teamSearch;
        $startDateSearch = $this->startDateSearch;
        $endDateSearch = $this->endDateSearch;
        $participantsSearch = $this->participantsSearch;
        $this->reset();
        $this->competitionSearch = $competitionSearch;
        $this->teamSearch = $teamSearch;
        $this->startDateSearch = $startDateSearch;
        $this->endDateSearch = $endDateSearch;
        $this->participantsSearch = $participantsSearch;
    }

    /**
    * The create function
    *
    * @return void
    */
    public function create() {
        $this->authorize('create',Competition::class);
        $this->validate();
        $competition = Competition::create($this->modelData());
        $competition->members()->sync($this->participants);
        $admins = User::where('role_id', Role::isSiteAdmin)->get();
        Notification::send($admins, new CreateCompetitionNotification(auth()->user(), $competition->id));

        // send Email to participants if requested and if there are participants
        if ($competition->members != null AND $this->sendMail == true) {
            $competitionArray = [];
            array_push($competitionArray, $competition);      
            foreach($competition->members as $member) {
                if($member->email != null) {
                    Mail::to($member->email)->send(new CompetitionsMail($competitionArray,$member->id));                    
                }
            }            
        }

        $this->modalFormVisible = false;
        $this->resetOnlyLivewireVariables();
    }

    /**
    * The read function
    *
    * @return void
    */
    public function read(){
        $this->authorize('view',Competition::class);
        if(empty($this->startDateSearch) && empty($this->endDateSearch)) {
            if($this->participantsSearch != null || $this->participantsSearch != []) {
                $records = Competition::orderBy('competition_date','Asc')
                ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                ->with('members')
                ->whereHas('members', function($member) {
                    $member->whereIn("lastname", $this->participantsSearch);
                })
                ->paginate(11);
            } else {
                $records = Competition::orderBy('competition_date','Asc')
                ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                ->with('members')
                ->paginate(11);
            }
            
        } else if(empty($this->startDateSearch)) {
            if($this->participantsSearch != null || $this->participantsSearch != []) {
                $records = Competition::orderBy('competition_date','Asc')
                    ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                    ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                    ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                    ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                    ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                    ->where('competition_date',"<=", $this->endDateSearch)
                    ->with('members')
                    ->whereHas('members', function($member) {
                        $member->whereIn("lastname", $this->participantsSearch);
                    })
                    ->paginate(11);
            } else {
                $records = Competition::orderBy('competition_date','Asc')
                    ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                    ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                    ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                    ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                    ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                    ->where('competition_date',"<=", $this->endDateSearch)
                    ->with('members')
                    ->paginate(11);
            }
        } else if(empty($this->endDateSearch)) {
            if($this->participantsSearch != null || $this->participantsSearch != []) {
                $records = Competition::orderBy('competition_date','Asc')
                    ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                    ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                    ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                    ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                    ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                    ->where('competition_date',">=", $this->startDateSearch)
                    ->with('members')
                    ->whereHas('members', function($member) {
                        $member->whereIn("lastname", $this->participantsSearch);
                    })
                    ->paginate(11);
            } else {
                $records = Competition::orderBy('competition_date','Asc')
                    ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                    ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                    ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                    ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                    ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                    ->where('competition_date',">=", $this->startDateSearch)
                    ->with('members')
                    ->paginate(11);
            }
        } else {
            if($this->participantsSearch != null || $this->participantsSearch != []) {
                $records = Competition::orderBy('competition_date','Asc')
                    ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                    ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                    ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                    ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                    ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                    ->whereBetween('competition_date', [$this->startDateSearch,$this->endDateSearch])
                    ->with('members')
                    ->whereHas('members', function($member) {
                        $member->whereIn("lastname", $this->participantsSearch);
                    })
                    ->paginate(11);
            } else {
                $records = Competition::orderBy('competition_date','Asc')
                    ->where('team_name','LIKE','%'.$this->teamSearch.'%')
                    ->where('competition',"LIKE",'%'.$this->competitionSearch.'%')
                    ->where('season',"LIKE",'%'.$this->seasonSearch.'%')
                    ->where('home_team',"LIKE",'%'.$this->homeSearch.'%')
                    ->where('visitor_team',"LIKE",'%'.$this->visitorSearch.'%')
                    ->whereBetween('competition_date', [$this->startDateSearch,$this->endDateSearch])
                    ->with('members')
                    ->paginate(11);
            }
        }
        return $records;

    }

    /**
    * The update function
    *
    * @return void
    */
    public function update(Competition $competition){
        $this->authorize('update', $competition);
        $this->validate();
        Competition::find($this->modelId)->update($this->modelData());
        Competition::find($this->modelId)->members()->sync($this->participants);
        $admins = User::where('role_id', Role::isSiteAdmin)->get();
        Notification::send($admins, new UpdateCompetitionNotification(auth()->user(), $this->modelId));
        
        // send Email to participants if requested and if there are participants
        $competition = Competition::find($this->modelId);
        if ($competition->members != null AND $this->sendMail == true) {
            $competitionArray = [];
            array_push($competitionArray, $competition);      
            foreach($competition->members as $member) {
                if($member->email != null) {
                    Mail::to($member->email)->send(new CompetitionsMail($competitionArray,$member->id));                    
                }
            }            
        }
        $this->modalFormVisible = false;
    }

    /**
    * The delete function
    *
    * @return void
    */
    public function delete(Competition $competition){
        $this->authorize('delete', $competition);
        Competition::destroy($this->modelId);
        $admins = User::where('role_id', Role::isSiteAdmin)->get();
        Notification::send($admins, new DeleteCompetitionNotification(auth()->user(), $this->modelId));
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
    * Function to show the create Modal
    *
    * @return void
    */
    public function createShowModal(){
        $this->resetValidation();
        $this->resetOnlyLivewireVariables();
        $this->competition = "undefined";
        $this->modalFormVisible = true;
    }

    /**
    * Function to show the update Modal
    *
    * @return void
    */
    public function updateShowModal($id){
        $this->resetValidation();
        $this->resetOnlyLivewireVariables();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }

    /**
    * Function to show the delete Modal
    *
    * @return void
    */
    public function deleteShowModal($id){
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

    /**
    * Function to search for Participants Modal
    *
    * @return void
    */
    public function searchModal(){
        $this->modalSearchVisible = true;
    }

    /**
    * Function to reset Search Criteria
    *
    * @return void
    */
    public function resetSearchCriteria(){
        $this->competition_search_name = "";
        $this->team_search_name = "";
        $this->start_date = "";
        $this->end_date = "";
        $this->participants_search = [];
    }

    /**
    * The render function
    *
    * @return void
    */
    public function render()
    {
        return view('livewire.admin.competitions',[
            'data' => $this->read(),
            'all_members' => $this->getMembers(),
            'team_names' => $this->getTeamNames(),
            'competition_names' => $this->getCompetitionNames(),
        ]);
    }
}