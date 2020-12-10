<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterStudent extends Model
{
    protected $guarded = [];

    public function model()
    {
        return $this->morphTo();
    }
}