<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Cmgmyr\Messenger\Traits\Messagable;

use App\Models\Institution;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Messagable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'user_name', 'first_name', 'middle_name', 'last_name', 'email', 'password', 'role', 'active', 'verified', 'about', 'verify_token', 'institution_id',
        'remember_token', 'headline', 'phone_number', 'country', 'state', 'city', 'address', 'zip', 'timezone', 'profession',
        'qualifications', 'achievements', 'experience'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Models\Course::class, 'course_user');
    }

    public function grade()
    {
        return $this->belongsToMany(Models\Grade::class, 'class_user');
    }

    public function division()
    {
        return $this->belongsToMany(Models\Division::class, 'division_user');
    }

    public function chapters()
    {
        return $this->hasMany(Models\ChapterStudent::class, 'user_id');
    }

    // Get Certificates
    public function certificates()
    {
        return $this->hasMany(Models\Certificate::class);
    }

    // Get Full Name
    public function fullName()
    {
        return !empty($this->first_name) ? $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name : $this->user_name ;
    }

    public function reviews()
    {
        return $this->morphMany(Models\Review::class, 'reviewable');
    }
}
