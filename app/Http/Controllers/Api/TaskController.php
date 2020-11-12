<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Laravue\Models\Task;
use App\Laravue\Models\Group;
use App\Http\Resources\TaskResource;
use App\Http\Resources\Groups;

class TaskController extends Controller
{
   const ITEM_PER_PAGE =10;
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $taskQuery = Task::query();
        $limit = Arr::get($searchParams,'limit',static::ITEM_PER_PAGE);
        $group = Arr::get($searchParams,'group','');
        $keyword = Arr::get($searchParams,'keyword','');   
         $current_page = Arr::get($searchParams,'current_page',1);
        if(!empty($group)) {
            $taskQuery->whereHas('group', function($q) use ($group) {$q->where('name',$group);});
        }

        if(!empty($keyword)) {
            $taskQuery ->where('title', 'LIKE','%' .$keyword. '%');
        }

        return TaskResource::collection($taskQuery->paginate($limit,['*'],'page',$current_page));
    }

  
    public function store(Request $request)
    {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
        foreach ($request->list as $data) {
            Task::create([
                'title'=>$data['title'],
                'start_date'=>$data['start_date'],
                'finish_date'=>$data['finish_date'],
                'user_id'=>$data['user_id'],
                'group_id'=>$data['group_id'],
            ]);
        }
      return response()->json(['message'=>'Create success','data'=>Task::all()],200);
      
    }

  
    public function show($id)
    {
        $task = Task::findOrFail($id);

        return new TaskResource($task);
    }

    public function update(Request $request, $id)
    {
      $task = Task::findOrFail($id);
      if($task ===null) {
          return response()->json([
          'message'=>'Task not found'
          ],404);
      }
      
      $validator= Validator::make($request->all(),$this->getValidationRules(false));

      if($validator->fails()) {
          return response()->json([
              'errors'=>$validator->errors()
          ],403);
      } else {
          $title = $request->get('title');
          $found = Task::where('title',$title)->first();

          if($found && $found->id !==$id) {
              return response()->json(['Title has been taken'],403);
          }

          
          $task->title= $title;
          $task->start_date= $request->get('start_date');
          $task->finish_date = $request->get('finish_date');
          $task->user_id = $request->get('user_id');
          $task->group_id = $request->get('group_id');
          $task->save();
          return new TaskResource($task);
      }


     
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
