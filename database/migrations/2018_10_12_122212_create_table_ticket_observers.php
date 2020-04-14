<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTicketObservers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('ticket_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('ticket_id', 'observers_fk_ticket')->references('id')->on('tickets');
            $table->foreign('user_id', 'observers_fk_user')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observers');
    }
}
