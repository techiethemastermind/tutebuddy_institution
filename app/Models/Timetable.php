<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = ['grade_id', 'name', 'division_id', 'type', 'table_type', 'order', 'url'];
}
