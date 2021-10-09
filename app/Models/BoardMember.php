<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    use HasFactory;

    protected $table = 'board_members' ;

    protected $fillable = ['name','title','address','postcode','city','phone','mobile'];

    protected $nullable = ['address','postcode','city','phone','mobile'];
}
