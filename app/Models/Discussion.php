<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Discussion extends Model
{
    protected $fillable = ['user_id', 'institution_id', 'grade_id', 'course_id', 'lesson_id', 'title', 'question', 'topics'];

    protected static function boot()
    {
        parent::boot();

        if(auth()->check() && !auth()->user()->hasRole('Student')) {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->where('institution_id', '=', auth()->user()->institution->id);
            });
        }

        if(auth()->check() && auth()->user()->hasRole('Student')) {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->where('institution_id', '=', auth()->user()->institution->id)->where('grade_id', '=', auth()->user()->grade[0]->id);
            });
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function topic($id)
    {
        $topic = DB::table('discussion_topics')->where('id', $id)->first();
        return $topic->topic;
    }

    public function results()
    {
        return $this->hasMany(DiscussionResults::class);
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
