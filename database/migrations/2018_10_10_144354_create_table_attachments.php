<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            $table->string('path');
            $table->bigInteger('ticket_id')->unsigned()->nullable();
            $table->bigInteger('message_id')->unsigned()->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_id', 'attachment_fk_ticket')->references('id')->on('tickets');
            $table->foreign('message_id', 'attachment_fk_message')->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
