<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Division extends Model
{
    protected static function boot()
    {
        parent::boot();

        if(!Auth::user()->hasRole('Administrator')) {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->where('institution_id', Auth::user()->institution->id);
            });
        }
    }
}
