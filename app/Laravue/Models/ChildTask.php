<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class ChildTask extends Model
{
     public $timestamps = false;
     
     protected $fillable = ['name','necessary_time','done','parent_task_id'];

     public function Task() {
         return $this->belongsTo('App\Laravue\Models\Task','parent_task_id','id');
     }
}
