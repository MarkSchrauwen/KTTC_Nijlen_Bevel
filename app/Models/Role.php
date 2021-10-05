<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = 'name';

    public const isSiteAdmin = 5;
    public const isContentManager = 4;
    public const isBlogModerator = 3;
    public const isTeamCaptain = 2;
    public const isMember = 1;
}
