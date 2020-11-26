<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Institution extends Model
{
	use SoftDeletes;
    protected $fillable = ['name', 'prefix', 'logo', 'email', 'phone_number', 'address', 'country', 'state', 'city', 'zip', 'timezone'];

    public function users()
    {
    	return $this->hasMany(User::class);
    }
}
