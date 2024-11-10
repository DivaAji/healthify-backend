<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('workouts', function (Blueprint $table) {
            $table->id('workout_id');           // Kolom ID workout
            $table->text('name');                // Kolom nama workout
            $table->string('category');          // Kolom kategori workout
            $table->text('description');         // Kolom deskripsi workout
            $table->integer('duration');         // Kolom durasi workout (dalam menit)
            $table->timestamps();                // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('workouts');
    }

};
