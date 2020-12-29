<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->integer('branches_no')->default(1);
            $table->integer('floor_no')->nullable();
            $table->integer('block_no')->nullable();
            $table->string('address')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('work_days')->nullable();
            $table->time('work_time_from')->nullable();
            $table->time('work_time_to')->nullable();
            $table->text('services')->nullable();
            $table->text('amenities')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('type')->default(1)->comment('1 => clinic, 2 => hospital');
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
        Schema::dropIfExists('clinics');
    }
}
