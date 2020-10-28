<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    //
    protected $guarded = [];

    public function eventDays(){
        return $this->hasMany(EventDay::class);
    }
}
