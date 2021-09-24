<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Member;
use Carbon\Carbon;

class Competition extends Model
{
    use HasFactory;

    protected $table = "competitions";

    protected $fillable = ["team_name","competition","season","competition_number",
        "competition_date","competition_time","home_team","visitor_team","competition_participants"];

    protected $dates = ["created_at","updated_at","competition_date"];

    public function members() {
        return $this->belongsToMany(Member::class,'competition_members')->withTimestamps();
    }
}
