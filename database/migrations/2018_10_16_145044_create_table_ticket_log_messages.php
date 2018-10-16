<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTicketLogMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_log_messages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('ticket_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('ip', 15)->nullable();
            $table->text('message');

            $table->timestamps();

            $table->foreign('user_id', 'ticket_log_fk_user')->references('id')->on('users');
            $table->foreign('ticket_id', 'ticket_log_fk_ticket')->references('id')->on('tickets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_log_messages');
    }
}
