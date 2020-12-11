<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Institution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\User;

class Grade extends Model
{
    public $table = 'classes';

    protected static function boot()
    {
        parent::boot();

        if(!Auth::user()->hasRole('Administrator')) {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->where('institution_id', Auth::user()->institution->id);
            });
        }
    }

    public function institution()
    {
    	return $this->belongsTo(Institution::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'class_id');
    }

    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'class_division');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_user');
    }

    public function classTimeTables()
    {
        return Timetable::where('grade_id', $this->id)->where('table_type', 0)->get();
    }

    public function examTimeTables()
    {
        return Timetable::where('grade_id', $this->id)->where('table_type', 1)->orderBy('order')->get();
    }

    public function classTimeTableForDivision($id)
    {
        return Timetable::where('grade_id', $this->id)->where('division_id', $id)->first();
    }
}
