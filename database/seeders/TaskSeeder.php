<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // {
        //     "skill" : {
        //         "id": 1,
        //         "name":"Terapija Update",
        //         "description":"Bog će ti platit v2"
        //     }
        // }

        DB::table('tasks')->updateOrInsert([
            'title' => 'Admin Task 1',    
            'description' => 'Description of task 1',    
            'status' => 0,
            'user_id' => '1',    
        ]);
        DB::table('tasks')->updateOrInsert([
            'title' => 'Admin Task 2',    
            'description' => 'Description of task 2',    
            'status' => 0,
            'user_id' => '1',    
        ]);
        DB::table('tasks')->updateOrInsert([
            'title' => 'Test user Task 1',    
            'description' => 'Description of task 1 from user',    
            'status' => 0,
            'user_id' => '2',    
        ]);
        DB::table('tasks')->updateOrInsert([
            'title' => 'Test user Task 2',    
            'description' => 'Description of task 2 from user',    
            'status' => 0,
            'user_id' => '2',    
        ]);
        DB::table('tasks')->updateOrInsert([
            'title' => 'Test user Task 3',    
            'description' => 'Description of task 3 from user',    
            'status' => 0,
            'user_id' => '2',    
        ]);
    }
}