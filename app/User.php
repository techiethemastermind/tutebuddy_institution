<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Institution;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'name', 'email', 'password', 'role', 'active', 'verified', 'about', 'verify_token',
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

    protected static function boot()
    {
        parent::boot();

        if(auth()->check() && auth()->user()->hasRole('Institution Admin')) {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->where('institution_id', '=', auth()->user()->institution->id);
            });
        }  

        static::creating(function ($user) {
            if(auth()->check() && auth()->user()->hasRole('Institution Admin')) {
                $user->institution_id = auth()->user()->institution_id;
            }
        });

    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
