<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Laravue\Models\Task;
use App\Laravue\Models\Group;
use App\Http\Resources\TaskResource;
use App\Http\Resources\Groups;
use User\Laravue\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
   const ITEM_PER_PAGE =10;
    public function index(Request $request)
    {   
       $searchParams = $request->all();
         if (empty($searchParams)) {
            return TaskResource::collection(Task::all());
         }else {
            
             $taskQuery = Task::query();
             $limit = Arr::get($searchParams,'limit',static::ITEM_PER_PAGE);
             $group = Arr::get($searchParams,'group','');
             $keyword = Arr::get($searchParams,'keyword','');   
             $current_page = Arr::get($searchParams,'page',1);
            if(!empty($group)) {
            $taskQuery->whereHas('group', function($q) use ($group) {$q->where('name',$group);});
             }

            if(!empty($keyword)) {
            $taskQuery ->where('title', 'LIKE','%' .$keyword. '%');
            }
    
             return TaskResource::collection($taskQuery->paginate($limit,['*'],'page',$current_page));
        }
       
    }

  
    public function store(Request $request)
    {                                                                                                                                                                              
        foreach ($request->list as $data) {
            Task::create([
                'title'=>$data['title'],
                'content'=>$data['content'],
                'start_date'=>$data['start_date'],
                'finish_date'=>$data['finish_date'],
                'user_id'=>$data['user'],
                'group_id'=>$data['group'],
            ]);
        }
      return response()->json(['message'=>'Create success','data'=>Task::all()],200);
      
    }

  
    public function show($id)
    {
        try {
           $task = Task::findOrFail($id);
           $start = Carbon::parse($task->start_date); 
           $workDays =0;
           $my_day_arr= [];
           $my_day_off_week =[];
           $end = Carbon::parse($task->finish_date);
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
          $child_tasks = $task->childTasks;
          $necessaryTimeTotal = 0;
          foreach($child_tasks as $child_task) {
           $necessaryTimeTotal+= $child_task->necessary_time;
          }
        } catch (ModelNotFoundException $exception) {
           return response()->json(['error'=>'Not found'],404);
            // return back()->withError($exception->getMessage())->withInput();
        }
         return response()->json(['data'=>new TaskResource($task),'statistic'=> ['diffDay'=>$days,'diffDayExceptHoliday'=>($workDays+1)*60*8, 'totalTimeOfChildTask'=>$necessaryTimeTotal,'compareTime'=>($workDays+1)*60*8-$necessaryTimeTotal]]);
        // return new TaskResource($task);
       
    }

    public function update(Request $request, $id)
    {
      $task = Task::findOrFail($id);
      if($task ===null) {
          return response()->json([
          'message'=>'Task not found'
          ],404);
      }
         
          try {
            if(!empty($request->get('title'))){
              $task->title= $request->get('title');
          }
          if( !empty($request->get('start_date'))) {
          $task->start_date= $request->get('start_date');
          }
          if( !empty($request->get('finish_date'))) {
             $task->finish_date = $request->get('finish_date');
          }
          
          if(!empty($request->get('user'))) {
            $task->user_id = $request->get('user');  
          }
          
          if(!empty($request->get('group'))) {
               $task->group_id = $request->get('group');
          }
         
          if(!empty($request->get('content'))) {
              $task->content = $request->get('content'); 
          }
         
          if(!empty($request->get('active'))) {
            $task->active = $request->get('active');   
          } 
         
          if($request->get('active_date') !== '') {
                $task->active_date = $request->get('active_date');
          }
        
          if(!empty($request->get('progress'))) {
               $task->progress = $request->get('progress');
          }
         
          if( !empty($request->get('complete_date'))) {
               $task->complete_date = $request->get('complete_date');
          }
         
          $task->save();
          } catch (Exception $e) {
          }
          return new TaskResource($task);
      }


    public function forceDelete(Request $request) {

        $list_id = $request->list_id;
      $result = Task::destroy($list_id);
      if($result ==0) {
          return response()->json([
              'message'=> 'Delete Nothing'
          ],200);
      }

      return response()->json([
       'message' => 'Delete Success',

      ],200);
    }
    public function destroy($id)
    {
        //
    }

    public function getList() {
      return TaskResource::collection(Task::all());
     
    }
    private function getValidationRules($isNew=true) {
        return [
            'title'=> $isNew ? 'required|unique:tasks' : 'required',
            'start_date'=> 'required',
            'finish_date'=> 'required'
        ];
    }
   
   
   
   
}
