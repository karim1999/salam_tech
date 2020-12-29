<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->date('birth_date')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1 => male, 2 => female');
            $table->string('sub_specialist')->nullable();
            $table->string('seniority_level')->nullable();
            $table->integer('floor_no')->nullable();
            $table->integer('block_no')->nullable();
            $table->string('address')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('work_days')->nullable();
            $table->time('work_time_from')->nullable();
            $table->time('work_time_to')->nullable();
            $table->double('fees')->default(0.0);
            $table->integer('patient_hour')->default(1);
            $table->boolean('home_visit')->default(0)->comment('0 => no, 1 => yes');
            $table->double('home_visit_fees')->default(0.0);
            $table->text('services')->nullable();
            $table->double('rate')->default(0.0);
            $table->integer('views')->default(0);
            $table->boolean('profile_finish')->default(0)->comment('0 => no, 1 => yes');
            $table->boolean('status')->default(1)->comment('0 => inactive, 1 => active');
            $table->unsignedBigInteger('specialist_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('clinic_id')->nullable();
            $table->unsignedBigInteger('clinic_branch_id')->nullable();

            $table->foreign('specialist_id')->references('id')->on('specialists')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('set null');
            $table->foreign('clinic_branch_id')->references('id')->on('clinic_branches')->onDelete('set null');
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
        Schema::dropIfExists('doctors');
    }
}
