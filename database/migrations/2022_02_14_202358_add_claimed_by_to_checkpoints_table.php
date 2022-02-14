<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimedByToCheckpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkpoints', function (Blueprint $table) {
            $table->unsignedBigInteger('claimed_by')->nullable();
            $table->foreign('claimed_by')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checkpoints', function (Blueprint $table) {
            Schema::dropIfExists('claimed_by');
        });
    }
}