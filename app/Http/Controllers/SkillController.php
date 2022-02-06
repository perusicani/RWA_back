<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use Validator;


class SkillController extends Controller
{
    // $table->string('name');
    // $table->longText('description')->nullable();

    public function show(Skill $skill) {

        return response()->json(['skill' => $skill]);
        
    }

    public function index() {
        
        //http://127.0.0.1:8000/api/skills?page=2
        $skills = Skill::paginate(10);
        
        return response()->json([
            'numberOfPages' => $skills->lastPage(),
            'skills' => $skills
        ]);

    }

    public function update(Request $request) {

        // {
        //     "skill" : {
        //         "id": 1,
        //         "name":"Terapija Update",
        //         "description":"Bog će ti platit v2"
        //     }
        // } 

        $validator = Validator::make($request->all(), [
            'skill' => 'required',
            'skill.name' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $skill = (object)$request->skill;

        $skillToUpdate = Skill::findOrFail($skill->id);

        $skillToUpdate->name = $skill->name;
        $skillToUpdate->description = $skill->description;

        $skillToUpdate->save();

        return response()->json([
            'skill' => $skillToUpdate,
        ]);
    }

    public function destroy($id) {

        Skill::findOrFail($id)->delete();

        return response()->json([
            'ok'
        ]);
    }

    public function create(Request $request) {

        // {
        //     "skill" : {
        //         "name":"Terapija Update",
        //         "description":"Bog će ti platit v2"
        //     }
        // }

        $validator = Validator::make($request->all(), [
            'skill' => 'required',
            'skill.name' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $skill = (object)$request->skill;

        $newSkill = Skill::create([
            'name' => $skill->name,
            'description' => $skill->description,
        ]);

        return response()->json([
            'skill' => $newSkill,
        ]);
    }
}