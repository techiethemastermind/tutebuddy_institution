<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = ['grade_id', 'division_id', 'type', 'url'];
}
