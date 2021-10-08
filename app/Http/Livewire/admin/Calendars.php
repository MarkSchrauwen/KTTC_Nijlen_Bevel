<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Goutte\Client;
use App\Exports\CalendarExport;
use App\Models\Competition;
use App\Models\CompetitionOrganisation;
use App\Models\CompetitionTeam;
use Excel;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Calendars extends Component
{
    use AuthorizesRequests;

    public $competitions = null;
    public $selectedCompetitionOrganisation;
    public $selectedSeason;
    public $selectedLevel = null;
    public $selectedClub = null;
    public $selectedDivision = null;
    public $selectedTeam;
    public $date;
    public $page;


    public $startVttlUrl = "https://competitie.vttl.be";
    public $calendarVttlUrl = "https://competitie.vttl.be/kalenders";
    public $startSportaUrl = "https://ttonline.sporta.be";
    public $calendarSportaUrl = "https://ttonline.sporta.be/kalenders";

    public function saveCompetition() {
        $this->authorize('create',Competition::class);
            foreach($this->competitions as $competition) {
                Competition::create([
                    "team_name" => $this->selectedTeam,
                    "competition" => $this->selectedCompetitionOrganisation,
                    "season" => $this->selectedSeason,
                    "competition_number" => $competition[0],
                    "competition_date" => $competition[1],
                    "competition_time" => $competition[4],
                    "home_team" => $competition[2],
                    "visitor_team" => $competition[3],
                ]);
            }           
        $this->reset();
    }

    public function filterClub($data,$club){

        $newarray = [];
        foreach($data as $row){
            foreach($row as $rowelement){
                if($rowelement === $club){
                    array_push($newarray,$row);
                }
            }
        }
        return $newarray;
    }

    public function getCalendarLevel() {
        $client = new Client();
        if($this->selectedCompetitionOrganisation == 'vttl') {
            $url = $this->calendarVttlUrl;
            $page = $client->request('GET', $url);
            $data = $page->filter('.clubcategory')->each(function($td) {
                $data_array = [];
                $data_array['value'] = $td->filter('a')->attr('href');
                $data_array['text'] = trim($td->filter('a')->text());
                return $data_array;
            });
            return $data;            
        } else if($this->selectedCompetitionOrganisation == 'sporta') {
            $url = $this->calendarSportaUrl;
            $page = $client->request('GET', $url);
            $data = $page->filter('.clubcategory')->each(function($td) {
                $data_array = [];
                $data_array['value'] = $td->filter('a')->attr('href');
                $data_array['text'] = trim($td->filter('a')->text());
                return $data_array;
            });
            return $data;
        }
        return null;
    }

    public function getCalendarClub() {
        $client = new Client();        
        if($this->selectedCompetitionOrganisation == "vttl") {
            if($this->selectedLevel == null || $this->selectedLevel == "") {
                $url = $this->calendarVttlUrl;
            } else {
                $url = $this->startVttlUrl . $this->selectedLevel;
            }        
            $page = $client->request('GET', $url);
            $data = $page->filter('#ClubList')->filter('option')->each(function($option) {
                $data_array = [];
                $data_array['value'] = $option->attr('value');
                $data_array['text'] = trim($option->text());
                return $data_array;
            });
            return $data;            
        } else if($this->selectedCompetitionOrganisation == "sporta") {
            $client = new Client();
            if($this->selectedLevel == null || $this->selectedLevel == "") {
                $url = $this->calendarSportaUrl;
            } else {
                $url = $this->startSportaUrl . $this->selectedLevel;
            }        
            $page = $client->request('GET', $url);
            $data = $page->filter('#ClubList')->filter('option')->each(function($option) {
                $data_array = [];
                $data_array['value'] = $option->attr('value');
                $data_array['text'] = trim($option->text());
                return $data_array;
            });
            return $data;
        }
        return null;
    }

    public function getCalendarDivision() {
        $client = new Client();
        if($this->selectedCompetitionOrganisation == "vttl") {
            if($this->selectedLevel == null || $this->selectedLevel == "") {
                $url = $this->calendarVttlUrl;
            } else {
                if($this->selectedClub == null || $this->selectedClub == "") {
                    $url = $this->startVttlUrl . $this->selectedLevel;
                } else {
                    $url = $this->startVttlUrl . $this->selectedLevel . '&club_id=' . $this->selectedClub;
                }            
            }
            
            $page = $client->request('GET', $url);
            $data = $page->filter('#DivList')->filter('option')->each(function($option) {
                $data_array = [];
                $data_array['value'] = $option->attr('value');
                $data_array['text'] = trim($option->text());
                return $data_array;
            });
            return $data;
        } else if($this->selectedCompetitionOrganisation == "sporta") {
            if($this->selectedLevel == null || $this->selectedLevel == "") {
                $url = $this->calendarSportaUrl;
            } else {
                if($this->selectedClub == null || $this->selectedClub == "") {
                    $url = $this->startSportaUrl . $this->selectedLevel;
                } else {
                    $url = $this->startSportaUrl . $this->selectedLevel . '&club_id=' . $this->selectedClub;
                }            
            }
            
            $page = $client->request('GET', $url);
            $data = $page->filter('#DivList')->filter('option')->each(function($option) {
                $data_array = [];
                $data_array['value'] = $option->attr('value');
                $data_array['text'] = trim($option->text());
                return $data_array;
            });
            return $data;
        }
        return null;
    }

    public function getCompetitionTeams() {
        $this->authorize('viewAny',CompetitionTeam::class);
        return CompetitionTeam::all();
    }

    public function getCompetitionOrganisation() {
        return CompetitionOrganisation::all();
    }

    public function getCalendarVTTL(){
        $client = new Client();
        if($this->selectedCompetitionOrganisation == "vttl") {
            if($this->selectedDivision != null && $this->selectedDivision != "" && 
            $this->selectedTeam != null && $this->selectedTeam != "") {

            $url = $this->startVttlUrl . $this->selectedLevel . '&club_id=' . $this->selectedClub . '&div_id=' . $this->selectedDivision;
            $page = $client->request('GET', $url);
            
            $data = $page->filter('.firstcolumn')->filter('tr')->each(function($tr){
                return $tr->filter('td')->each(function($td){
                        return trim($td->text());
                });
            });
            $competitions = $this->filterClub($data,$this->selectedTeam);

            $data = $page->filter('.secondcolumn')->filter('tr')->each(function($tr){
                return $tr->filter('td')->each(function($td){
                        return trim($td->text());
                });
            });
            $competitions = array_merge($competitions,$this->filterClub($data,$this->selectedTeam));
            foreach($competitions as $key1=>$competition) {
                $competitions[$key1] = array_filter($competition);
            }
            for($i=0;$i <= count($competitions) - 1;$i++) {
                $competitions[$i][4] = $this->readTime($competitions[$i][1]);
                $competitions[$i][1] = $this->readDate($competitions[$i][1]);                
            }
            $this->competitions = $competitions;
            return $competitions;            
        }
        } else if($this->selectedCompetitionOrganisation == "sporta") {
            if($this->selectedDivision != null && $this->selectedDivision != "" && 
            $this->selectedTeam != null && $this->selectedTeam != "") {

            $url = $this->startSportaUrl . $this->selectedLevel . '&club_id=' . $this->selectedClub . '&div_id=' . $this->selectedDivision;
            $page = $client->request('GET', $url);
            
            $data = $page->filter('.firstcolumn')->filter('tr')->each(function($tr){
                return $tr->filter('td')->each(function($td){
                        return trim($td->text());
                });
            });
            $competitions = $this->filterClub($data,$this->selectedTeam);

            $data = $page->filter('.secondcolumn')->filter('tr')->each(function($tr){
                return $tr->filter('td')->each(function($td){
                        return trim($td->text());
                });
            });
            $competitions = array_merge($competitions,$this->filterClub($data,$this->selectedTeam));
            foreach($competitions as $key1=>$competition) {
                $competitions[$key1] = array_values(array_filter($competition));
            }

            for($i=0;$i <= count($competitions) - 1;$i++) {
                $competitions[$i][4] = $this->readTime($competitions[$i][1]);
                $competitions[$i][1] = $this->readDate($competitions[$i][1]);                
            }
            $this->competitions = $competitions;
            return $competitions;            
        }
        }
        return null;
    }

    public function getCalendarSporta(){

        $client = new Client();
        $url = "https://ttonline.sporta.be/kalenders";
        $page = $client->request('GET', $url);
        
        $url = "https://ttonline.sporta.be/index.php?type=3&div_id=1478_E";
        $page = $client->request('GET', $url);
        // echo $page->html();
        $data = $page->filter('.firstcolumn')->filter('tr')->each(function($tr){
            return $tr->filter('td')->each(function($td){
                if($td->text() == ""){
                    return;
                } else {
                    return trim($td->text());
                }
            });
        });
        $scompetitions = $this->filterClub($data,$this->club);

        $data = $page->filter('.secondcolumn')->filter('tr')->each(function($tr){
            return $tr->filter('td')->each(function($td){
                if($td->text() == ""){
                    return;
                } else {
                    return trim($td->text());
                }
            });
        });
        $scompetitions = array_merge($scompetitions,$this->filterClub($data,"Bevel F"));
        foreach($scompetitions as $key1=>$competition) {
            $scompetitions[$key1] = array_filter($competition);
        }
        for($i=0;$i <= count($scompetitions) - 1;$i++) {
            $date = $this->readDate($scompetitions[$i][2]);                
        }
        $this->scompetitions = $scompetitions;
        return $scompetitions;

    }

    public function readDate($dateString) {
        if($dateString == "-"){
            return null;
        } else {
            $date_array = explode(' ',$dateString);
            $dateString = explode("-",$date_array[1]);
            $date = Carbon::createFromDate($dateString[2]+2000,$dateString[1],$dateString[0])->toDateString();
            return $date;            
        }
    }

    public function readTime($dateString) {
        if($dateString == "-"){
            return null;
        } else {
            $date_array = explode(' ',$dateString);
            $timeString = explode(":",$date_array[3]);
            $time = Carbon::createFromTime($timeString[0],substr($timeString[1],0,2))->toTimeString();
            return $time;            
        }
    }    

    public function exportToExcel() {

        return Excel::download(new CalendarExport, 'TTCompetitie.xlsx');
    }

    public function render()
    {
        return view('livewire.admin.calendars',[
            "competition_organisations" => $this->getCompetitionOrganisation(),
            "levels" => $this->getCalendarLevel(),
            "clubs" => $this->getCalendarClub(),
            "divisions" => $this->getCalendarDivision(),
            "competition_teams" => $this->getCompetitionTeams(),
            "competitions" => $this->getCalendarVTTL(),
        ]);
    }
}
