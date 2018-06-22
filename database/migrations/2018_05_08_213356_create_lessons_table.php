<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->integer('module_id')->unsigned()->index()->nullable();
            $table->foreign('module_id')->references('id')->on('modules');

            $table->integer('previous_lesson_id')->unsigned()->index()->nullable();
            $table->foreign('previous_lesson_id')->references('id')->on('lessons');

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
        Schema::dropIfExists('lessons');
    }
}
