<?php
// database/migrations/2024_11_10_000005_create_progress_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressTable extends Migration
{
    public function up()
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->foreignId('user_id')->constrained('users');
            $table->float('weight');
            $table->dateTime('recorded_at')->useCurrent();
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('progress');
    }
}
