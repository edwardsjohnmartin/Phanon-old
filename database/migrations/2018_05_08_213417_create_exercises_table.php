<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->increments('id');
            $table->text('prompt');
            $table->text('pre_code')->nullable();
            $table->text('start_code')->nullable();
            $table->text('test_code');
            $table->text('solution')->nullable();
            
            $table->integer('lesson_id')->unsigned()->index()->nullable();
            $table->foreign('lesson_id')->references('id')->on('lessons');

            $table->integer('previous_exercise_id')->unsigned()->index()->nullable();
            $table->foreign('previous_exercise_id')->references('id')->on('exercises');

            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users');

            $table->timestamps();

            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}
