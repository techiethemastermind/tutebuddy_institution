<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Institution;

class Grade extends Model
{
    public $table = 'classes';

    public function institution()
    {
    	return $this->belongsTo(Institution::class);
    }
}
