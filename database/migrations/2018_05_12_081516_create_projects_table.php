<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->dateTime('open_date');
            $table->dateTime('close_date');
            $table->text('prompt');
            $table->text('pre_code')->nullable();
            $table->text('start_code')->nullable();
            $table->text('solution')->nullable();

            $table->integer('module_id')->unsigned()->index()->nullable();
            $table->foreign('module_id')->references('id')->on('modules');

            $table->integer('previous_lesson_id')->unsigned()->index()->nullable();
            $table->foreign('previous_lesson_id')->references('id')->on('lessons');
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('projects');
    }
}
