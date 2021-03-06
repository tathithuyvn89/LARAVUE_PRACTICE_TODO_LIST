<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('content');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->integer('active')->default(0)->nullable();
            $table->date('active_date')->nullable();
            $table->integer('progress')->default(0)->nullable();
            $table->date('complete_date')->nullable();
            $table->date('start_date');           
            $table->date('finish_date');
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
        Schema::dropIfExists('tasks');
    }
}
