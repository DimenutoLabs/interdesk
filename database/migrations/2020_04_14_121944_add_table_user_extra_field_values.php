<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableUserExtraFieldValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_extra_field_values', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('user_extra_field_id')->unsigned();
            $table->string('value');

            $table->timestamps();

            $table->foreign('user_id', 'user_fk_extra_field')->references('id')->on('users');
            $table->foreign('user_extra_field_id', 'field_value_fk_extra_field')->references('id')->on('user_extra_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_extra_field_values');
    }
}
