<?php
// database/migrations/2024_11_10_000007_create_notifications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('user_id')->constrained('users');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->dateTime('sent_at')->useCurrent();
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
