<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkpoint;
use Validator;

class CheckpointController extends Controller
{
    public function show(Checkpoint $checkpoint) {

        return response()->json(['checkpoint' => $checkpoint]);
        
    }

    public function index() {
        
        $checkpoints = Checkpoint::get();
        
        return response()->json([
            'checkpoints' => $checkpoints
        ]);

    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'checkpoint' => 'required',
            'checkpoint.description' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $checkpoint = (object)$request->checkpoint;

        $checkpointToUpdate = Checkpoint::findOrFail($checkpoint->id);

        $checkpointToUpdate->description = $checkpoint->description;
        $checkpointToUpdate->status = $checkpoint->status;

        $checkpointToUpdate->save();

        return response()->json([
            'checkpoint' => $checkpointToUpdate,
        ]);
    }

    public function destroy($id) {

        Checkpoint::findOrFail($id)->delete();

        return response()->json([
            'ok'
        ]);
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'checkpoint' => 'required',
            'checkpoint.description' => 'required',
            'task_id' => 'required|exists:tasks,id',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $checkpoint = (object)$request->checkpoint;

        $task_id = $request->task_id;

        $newCheckpoint = Checkpoint::create([
            'description' => $checkpoint->description,
            'status' => 0,
            'task_id' => $task_id,
        ]);

        return response()->json([
            'checkpoint' => $newCheckpoint,
        ]);
    }
}