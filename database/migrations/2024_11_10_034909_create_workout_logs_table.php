<?php

// database/migrations/2024_11_10_000004_create_workout_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutLogsTable extends Migration
{
    public function up()
    {
        Schema::create('workout_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('workout_id')->constrained('workouts');
            $table->date('date');
            $table->integer('duration')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('workout_logs');
    }
}

