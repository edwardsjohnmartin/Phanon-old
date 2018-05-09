<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_module', function (Blueprint $table) {
            $table->integer('course_id')->unsigned()->nullable($value = false);
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->integer('module_id')->unsigned()->nullable($value = false);
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');

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
        Schema::dropIfExists('course_module');
    }
}
