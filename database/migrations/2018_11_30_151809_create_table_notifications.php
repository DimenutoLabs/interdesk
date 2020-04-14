<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('ticket_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('message');
            $table->string('url');
            $table->boolean('read');

            $table->timestamps();

            $table->foreign('user_id', 'notify_fk_user')->references('id')->on('users');
            $table->foreign('ticket_id', 'notify_fk_ticket')->references('id')->on('tickets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
