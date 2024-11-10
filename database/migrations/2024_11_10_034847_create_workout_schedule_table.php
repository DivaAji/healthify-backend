<?php
// database/migrations/2024_11_10_000003_create_workout_schedule_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutScheduleTable extends Migration
{
    public function up()
    {
        Schema::create('workout_schedule', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('workout_id')->constrained('workouts');
            $table->date('scheduled_date');
            $table->boolean('completed')->default(false);
            $table->dateTime('completion_time')->nullable();
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('workout_schedule');
    }
}
