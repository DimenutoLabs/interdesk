<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAgents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_users', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('tickets_id')->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tickets_id', 'ticket_user_fk_ticket')->references('id')->on('tickets');
            $table->foreign('user_id', 'ticket_user_fk_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_users');
    }
}
