<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionTeam extends Model
{
    use HasFactory;

    protected $table = "competition_teams";

    protected $fillable = ["name"];
}
