<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExerciseLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercise_lesson', function (Blueprint $table) {
            $table->integer('exercise_id')->unsigned()->nullable($value = false);
            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');

            $table->integer('lesson_id')->unsigned()->nullable($value = false);
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');

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
        Schema::dropIfExists('exercise_lesson');
    }
}
