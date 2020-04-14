<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableUserExtraFieldTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_extra_field_types', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('cast');
            $table->boolean('is_numeric')->default(false);
            $table->boolean('is_float')->default(false);
            $table->boolean('is_string')->default(false);
            $table->boolean('is_boolean')->default(false);
            $table->boolean('is_file')->default(false);
            $table->boolean('is_date')->default(false);
            $table->boolean('is_time')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_extra_field_types');
    }
}
