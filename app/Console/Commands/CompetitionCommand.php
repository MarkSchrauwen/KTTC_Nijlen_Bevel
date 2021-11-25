<?php

namespace App\Console\Commands;

use App\Mail\CompetitionsMail;
use App\Models\Competition;
use App\Models\Member;
use ArrayObject;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Mail;

class CompetitionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'competition:remindermail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send remindermails to participants of a competition 48 hours beforehand';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get all competitions with participants present,not confirmed,
        // less than 48 hours away
        $competitions = Competition::where('mailConfirmed',"!=",1)
            ->where('competition_date','<=',Carbon::now()->addDays(2)->startOfDay())
            ->where('competition_date','>=', Carbon::now()->startOfDay())
            ->with('members')
            ->whereHas('members')
            ->get();


        if($competitions != null) {

            // group them by member(email) - check for not empty
            $mailingArray = [];
            $membersToMail = [];            
            foreach($competitions as $competition) {
                foreach($competition->members as $member) {
                    array_push($membersToMail,$member->id);
                    $mailingArray[$member->id][] = $competition->toArray();                  
                }
            }
            $membersToMail = array_unique($membersToMail);
            
            // send email and set reminderEmail to true
            foreach($membersToMail as $memberId) {
                $member = Member::find($memberId);
                $memberEmail = $member->email;
                if($memberEmail != null) {
                    Mail::to($memberEmail)->send(new CompetitionsMail($mailingArray[$memberId],$memberId));                  
                }               
            }
            
            // set all mailConfirmed of concerned competitions to 1
            Competition::where('mailConfirmed',"!=",1)
            ->where('competition_date','<=',Carbon::now()->addDays(2)->startOfDay())
            ->where('competition_date','>=', Carbon::now()->startOfDay())
            ->with('members')
            ->whereHas('members')
            ->update(['mailConfirmed' => 1]);
        }
    }
}
