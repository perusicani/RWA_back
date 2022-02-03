<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_id');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('CASCADE');
            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skill_tasks');
    }
}