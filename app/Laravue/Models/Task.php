<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =['title','start_date','finish_date','user_id','group_id','content',
    'active','active_date','complete_date', 'progress'];

    public function group() {
        return $this->belongsTo('App\Laravue\Models\Group');
    }

    public function user(){
        return $this->belongsTo('App\Laravue\Models\User');

    }

    public function childTasks() {
        return $this->hasMany('App\Laravue\Models\ChildTask','parent_task_id');
    }
}
