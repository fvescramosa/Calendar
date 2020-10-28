<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDay extends Model
{
    //
    protected $guarded = [];

    public function event(){
        return $this->belongsTo(Events::class);
    }
}
