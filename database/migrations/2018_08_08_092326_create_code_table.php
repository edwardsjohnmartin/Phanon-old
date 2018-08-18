<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_exercises', function (Blueprint $table) {
            $table->increments('id');

            $table->text('prompt');
            $table->text('pre_code')->nullable();
            $table->text('start_code')->nullable();
            $table->text('test_code');
            $table->text('solution')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('code_exercises');
    }
}
