<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->updateOrInsert([
            'name' => 'Programming',    
        ]);
        DB::table('skills')->updateOrInsert([
            'name' => 'Math',    
        ]);
        DB::table('skills')->updateOrInsert([
            'name' => 'Cleaning',    
        ]);
        DB::table('skills')->updateOrInsert([
            'name' => 'Cooking',    
        ]);
        DB::table('skills')->updateOrInsert([
            'name' => 'Writing',    
        ]);
        DB::table('skills')->updateOrInsert([
            'name' => 'Dizanje teških stvari',    
        ]);
        DB::table('skills')->updateOrInsert([
            'name' => 'Therapy',    
        ]);
        DB::table('skills')->updateOrInsert([
            'name' => 'Drawing',    
        ]);
    }
}