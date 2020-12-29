<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('identification_card')->nullable();
            $table->string('insurance_card')->nullable();
            $table->date('birth_date')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1 => male, 2 => female');
            $table->integer('floor_no')->nullable();
            $table->integer('block_no')->nullable();
            $table->string('address')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->double('rate')->default(0.0);
            $table->integer('points')->default(0);
            $table->boolean('profile_finish')->default(0)->comment('0 => no, 1 => yes');
            $table->boolean('status')->default(1)->comment('0 => inactive, 1 => active');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
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
        Schema::dropIfExists('users');
    }
}
