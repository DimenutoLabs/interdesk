<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableUserExtraFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_extra_fields', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->bigInteger('user_extra_field_type_id')->unsigned();
            $table->string('max_value')->nullable();
            $table->string('min_value')->nullable();
            $table->integer('max_length')->nullable();
            $table->string('mask')->nullable();

            $table->timestamps();

            $table->foreign('user_extra_field_type_id', 'field_type_fk_extra_field')->references('id')->on('user_extra_field_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_extra_fields');
    }
}
