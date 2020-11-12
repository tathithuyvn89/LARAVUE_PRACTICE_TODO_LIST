<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable =['name'];

    public function users() {
        return $this->belongsToMany('App\Laravue\Models\User','users_groups');

    }

    public function tasks(){
        return $this->hasMany('App\Laravue\Models\Task');
    }

}
