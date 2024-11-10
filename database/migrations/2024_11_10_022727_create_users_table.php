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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');                // Kolom ID user
            $table->string('username');            // Kolom username
            $table->string('email')->unique();     // Kolom email yang unik
            $table->string('password');            // Kolom password
            $table->string('gender');              // Kolom gender
            $table->float('weight');               // Kolom berat badan
            $table->float('height');               // Kolom tinggi badan
            $table->integer('age');                // Kolom usia
            $table->timestamps();                  // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
