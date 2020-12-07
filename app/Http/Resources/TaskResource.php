<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

      //  $start = Carbon::parse($this->finish_date); 
      //      $workDays =0;
      //      $my_day_arr= [];
      //      $my_day_off_week =[];
      //      if($this->complete_date!=null){
      //         $end = Carbon::parse($this->complete_date);
      //      $days = $end->diffInDays($start);
      //     for($i=0; $i<$days;$i++) {
      //       $start->addDays();
      //     array_push($my_day_off_week,$start->dayOfWeek);   
      //     }
      //     for($i=0;$i<sizeof($my_day_off_week); $i++) {
      //       if($my_day_off_week[$i] !=6 &&  $my_day_off_week[$i] !=0){
      //           $workDays++;  
      //       } 
      //     }
        
      //      }else {
      //        $workDays= -100;
      //      }
          
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'content'=>$this->content,
            'start_date'=>$this->start_date,
            'finish_date'=>$this->finish_date,
            'active'=>$this->active,
            'active_date'=>$this->active_date,
            'progress'=>$this->progress,
            'complete_date'=>$this->complete_date,
            'complete_level'=>$this->calculateCompleteLevel($this->finish_date,$this->complete_date),
            'user'=>$this->user,
            'group'=>$this->group,
            "child_tasks"=>$this->childTasks,
        ];
    }
    private function calculateCompleteLevel($start, $end){
         $start = Carbon::parse($start); 
           $workDays =0;
           $my_day_arr= [];
           $my_day_off_week =[];
           if($end!=null){
              $end = Carbon::parse($end);
           $days = $end->diffInDays($start);
          for($i=0; $i<$days;$i++) {
            $start->addDays();
          array_push($my_day_off_week,$start->dayOfWeek);   
          }
          for($i=0;$i<sizeof($my_day_off_week); $i++) {
            if($my_day_off_week[$i] !=6 &&  $my_day_off_week[$i] !=0){
                $workDays++;  
            } 
          }
         return $workDays;
           }else {
             return 'NAN';
           }
          
    }
}
