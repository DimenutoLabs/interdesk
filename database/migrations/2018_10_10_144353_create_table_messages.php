<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('ticket_id')->unsigned();
            $table->longText('message');
            $table->integer('attachment_id')->unsigned();


            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_id', 'message_fk_ticket')->references('id')->on('tickets');
            $table->foreign('attachment_id', 'message_fk_attachment')->references('id')->on('attachments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
