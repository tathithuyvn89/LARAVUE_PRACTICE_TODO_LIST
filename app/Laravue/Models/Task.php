<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =['title','start_date','finish_date','user_id','group_id'];

    public function group() {
        return $this->belongsTo('App\Laravue\Models\Group');
    }

    public function user(){
        return $this->belongsTo('App\Laravue\Models\User');

    }
}
