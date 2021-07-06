<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

use Cmgmyr\Messenger\Traits\Messagable;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

use Illuminate\Support\Facades\DB;
use Mail;

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
        'qualifications', 'achievements', 'experience', 'roll_no'
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

    // Return student's grade
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

    public function notify_message()
    {
        $userId = $this->id;
        $threads = Thread::where('subject', 'like', '%' . $userId . '%')->latest('updated_at')->get();
        $partners = [];

        foreach($threads as $thread) {

            if($thread->userUnreadMessagesCount($userId) > 0) {

                $grouped_participants = $thread->participants->where('user_id', '!=', $userId)->groupBy(function($item) {
                    return $item->user_id;
                });

                foreach($grouped_participants as $participants) {
                    $participant = $participants[0];

                    $item = [
                        'partner_id' => $participant->user_id,
                        'unread' => $thread->userUnreadMessagesCount($userId),
                        'msg' => $thread->latestMessage
                    ];
                    array_push($partners, $item);
                }
            }
        }

        return $partners;
    }
}
