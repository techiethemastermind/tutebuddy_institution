<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Lesson extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }

    public function chapterStudents()
    {
        return $this->morphMany(ChapterStudent::class, 'model');
    }

    public function lessonDuration()
    {
        return $this->steps->sum('duration');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get completed status
     */
    public function isCompleted()
    {
        $c = ChapterStudent::where('model_type', Lesson::class)->where('model_id', $this->id)->get();

        if(count($c) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
