<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Exports\CalendarExport;
use Excel;

class WebScraper extends Controller
{
    protected $competitions;
    public $page;
    public $url;

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

    public function getCalendarVTTL(){
        $this->club = "Nijlen E";
        $client = new Client();
        $url = "https://competitie.vttl.be/kalenders";
        $page = $client->request('GET', $url);
        
        $url = "https://competitie.vttl.be/?season=22&province=4&div_id=5414&type=3&modif=&week_name=01&club_id=780&club_id=302";
        $page = $client->request('GET', $url);
        // echo $page->html();
        $data = $page->filter('.firstcolumn')->filter('tr')->each(function($tr){
            return $tr->filter('td')->each(function($td){
                    return trim($td->text());
            });
        });
        $competitions = $this->filterClub($data,$this->club);

        $data = $page->filter('.secondcolumn')->filter('tr')->each(function($tr){
            return $tr->filter('td')->each(function($td){
                    return trim($td->text());
            });
        });
        $competitions = array_merge($competitions,$this->filterClub($data,$this->club));
        Excel::download(new CalendarExport($competitions),'calendar.xlsx');
        return view('calendar',["competitions" => $competitions]);

    }

    public function exportIntoExcel($competitions){

        return 'geslaagd';
    }
}
