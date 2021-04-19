<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Quiz extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (auth()->user()->hasRole('Teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    // $builder->where('user_id', auth()->user()->id);

                    $builder->whereHas('course', function ($q) {
                        $q->where('user_id', '=', Auth::user()->id);
                    });
                });
            }

            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('published', 1);
                });
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id')->withTrashed();
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id')->withTrashed();
    }

    public function questions()
    {
        return $this->morphMany(Question::class, 'model');
    }

    public function question_groups()
    {
        return $this->morphMany(QuestionGroup::class, 'model');
    }

    public function step()
    {
        return $this->hasOne(Step::class, 'quiz', 'id');
    }

    public function result()
    {
        return $this->hasOne(QuizResults::class);
    }

    public function chapterStudents()
    {
        return $this->morphMany(ChapterStudent::class, 'model');
    }

    public function isCompleted(){
        $isCompleted = $this->chapterStudents()->where('user_id', \Auth::id())->count();
        if($isCompleted > 0){
            return true;
        }
        return false;
    }
}
