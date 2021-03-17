<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\User;
use App\Models\Lesson;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        if(!Auth::user()->hasRole('Administrator')) {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->where('institution_id', Auth::user()->institution->id);
            });

            if(Auth::user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('class_id', Auth::user()->grade[0]->id);
                });
            }

            if(Auth::user()->hasRole('Teacher')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('institution_id', Auth::user()->institution->id);

                    $builder->whereHas('teachers', function ($q) {
                        $q->where('course_user.user_id', '=', auth()->user()->id);
                    });
                });
            };
        }

        static::deleting(function ($course) { // before delete() method call this
            if ($course->isForceDeleting()) {
                if (File::exists(public_path('/storage/uploads/' . $course->course_image))) {
                    File::delete(public_path('/storage/uploads/' . $course->course_image));
                    File::delete(public_path('/storage/uploads/thumb/' . $course->course_image));
                }
            }
        });
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_user')->withPivot('user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student')->withTimestamps()->withPivot(['rating']);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function publishedLessons()
    {
        return $this->hasMany(Lesson::class)->where('published', 1);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function mediaVideo()
    {
        $types = ['video', 'pdf', 'audio', 'embed'];
        return $this->morphOne(Media::class, 'model')
            ->whereIn('type', $types);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function duration()
    {
        $d = 0;

        foreach($this->lessons as $lesson) {
            $d += $lesson->lessonDuration();
        }

        $hours = floor($d / 60);
        $minutes = ($d % 60);

        return $hours . 'h ' . $minutes . 'm';
    }

    public function progress()
    {
        if(auth()->check()) {
            $completed_lessons = \Auth::user()->chapters()
                ->where('model_type', Lesson::class)
                ->where('course_id', $this->id)
                ->pluck('model_id')->toArray();
            
            if (count($completed_lessons) > 0) {
                return intval(count($completed_lessons) / $this->lessons->count() * 100);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function isUserCertified()
    {
        $status = false;
        $certified = auth()->user()->certificates()->where('course_id', '=', $this->id)->first();
        if ($certified != null) {
            $status = true;
        }
        return $status;
    }

    public function isCompleted()
    {
        $status = false;
        $chapter = DB::table('chapter_students')->where('user_id', auth()->user()->id)->where('course_id', $this->id)->first();
        if($chapter != null) {
            $status = true;
        }
        return $status;
    }

    public function isReviewed()
    {
        return (auth()->check() && $this->reviews()->where('user_id', '=', auth()->user()->id)->count() > 0);
    }

    public function isFavorited()
    {
        $favorite = DB::table('course_favorite')->where('course_id', $this->id)->where('user_id', auth()->user()->id)->first();
        return ($favorite) ? true : false;
    }
}
