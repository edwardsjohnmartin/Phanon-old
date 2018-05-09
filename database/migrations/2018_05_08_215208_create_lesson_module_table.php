<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_module', function (Blueprint $table) {
            $table->integer('lesson_id')->unsigned()->nullable($value = false);
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');

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
        Schema::dropIfExists('lesson_module');
    }
}
