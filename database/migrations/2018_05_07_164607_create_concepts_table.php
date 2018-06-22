<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->integer('course_id')->unsigned()->index()->nullable();
            $table->foreign('course_id')->references('id')->on('courses');

            $table->integer('previous_concept_id')->unsigned()->index()->nullable();
            $table->foreign('previous_concept_id')->references('id')->on('concepts');

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
        Schema::dropIfExists('concepts');
    }
}
