<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Lesson;
use App\Models\Course;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = ['course_id', 'lesson_id', 'user_id', 'title', 'content', 'total_mark', 'attachment', 'due_date', 'published'];

    protected static function boot()
    {
        parent::boot();
        
        if (auth()->check()) {
            if (auth()->user()->hasRole('Teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    // $builder->where('user_id', '=', Auth::user()->id);

                    $builder->whereHas('course', function ($q) {
                        $q->where('user_id', '=', Auth::user()->id);
                    });
                });
            }

            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    // $builder->where('published', 1);
                    $builder->whereHas('course', function ($q) {
                        $q->where('published', 1);
                    });
                });
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function result()
    {
        return $this->hasOne(AssignmentResult::class);
        // return AssignmentResult::where('assignment_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }
}
