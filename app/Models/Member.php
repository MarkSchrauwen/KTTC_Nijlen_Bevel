<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Competition;

class Member extends Model
{
    use HasFactory;

    protected $table = "members";

    protected $fillable = [
        "user_id", "name", "email", "address", "postal_code", "city", "phone", "mobile",
        "birthdate",
    ];

    public function user() {
        $this->belongsTo(User::class);
    }

    public function competitions() {
        return $this->belongsToMany(Competition::class,'competition_members')->withTimestamps();
    }
}
