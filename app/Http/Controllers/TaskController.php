<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Validator;


class TaskController extends Controller
{
    public function id(Request $request) {

        $task = Task::with('checkpoints', 'skills')->findOrFail($request->id);

        return response()->json(['task' => $task]);
        
    }

    public function show(Task $task) {

        return response()->json(['task' => $task]);
        
}

    public function index(Request $request) {
        
        //http://127.0.0.1:8000/api/tasks?page=2
        // YOOOOOOOOOO OVAKO SE ZOVU ELOQUENT RELATIONSHIPS
        // https://stackoverflow.com/questions/62127621/retrieving-data-with-foreign-keys-laravel-react
        // ->where('title', 'LIKE', "%{$search}%")

        // {
        //     "search" : "programming",
        //     "completed" : "1"
        // }

        $search = $request->search;
        $completed = $request->completed;
        
        $tasks = Task::with('checkpoints', 'skills')
            ->when($completed, function($query) use ($completed) { 
                return $query->where('status', '=',  $completed); 
            })
            ->when($search, function($query) use ($search) { 
                //TODO: case insensitive if needed
                return $query->where('title', 'LIKE', "%{$search}%"); 
            })
            ->paginate(10);
        
        return response()->json([
            'numberOfPages' => $tasks->lastPage(),
            'tasks' => $tasks
        ]);

    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'task' => 'required',
            'task.title' => 'required',
            'task.description' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $task = (object)$request->task;

        $taskToUpdate = Task::findOrFail($task->id);

        $taskToUpdate->title = $task->title;
        $taskToUpdate->description = $task->description;
        $taskToUpdate->status = $task->status;

        $taskToUpdate->save();

        return response()->json([
            'task' => $taskToUpdate,
        ]);
    }

    public function destroy($id) {

        Task::findOrFail($id)->delete();

        return response()->json([
            'ok'
        ]);
    }

    public function create(Request $request) {

        // $table->string('title');
        // $table->longText('description');
        // $table->integer('status');  // 0 == incomplete, 1 == in progress, 2 == complete
        // $table->unsignedBigInteger('user_id');

        $validator = Validator::make($request->all(), [
            'task' => 'required',
            'task.title' => 'required',
            'task.description' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $task = (object)$request->task;

        $user_id = $request->user_id;

        $newTask = Task::create([
            'title' => $task->title,
            'description' => $task->description,
            'status' => 0,
            'user_id' => $user_id,
        ]);

        return response()->json([
            'task' => $newTask,
        ]);
    }
    
    public function addSkills(Request $request) {

        // {
        //     "taskId":31,
        //     "skillIds": [1]
        // }

        $validator = Validator::make($request->all(), [
            'taskId' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $taskId = $request->taskId;
        $task = Task::findOrFail($taskId);

        //detach previous -> so i don't need to filter in UI
        $task->skills()->detach();

        foreach ($request->skillIds as $skillId) {
            //attach skill
            $task->skills()->attach($skillId);    
        }


        return response()->json([
            'task' => $task,
            'skillIds' => $request->skillIds,
        ]);

    }
}