<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ScheduleTimeTable extends Model
{
    public $table = 'schedule_timetable';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        
        if (auth()->check()) {

            if(auth()->user()->hasRole('Institution Admin')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('institution_id', auth()->user()->institution_id);
                });
            }

            if (auth()->user()->hasRole('Teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('user_id', auth()->user()->id);
                });
            }

            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    // 
                });
            }
        }
    }
}
