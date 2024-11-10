<?php
// database/migrations/2024_11_10_000006_create_goals_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id('goal_id');
            $table->foreignId('user_id')->constrained('users');
            $table->text('description');
            $table->date('target_date');
            $table->string('status', 20)->default('ongoing');
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
