<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name')->nullable();
            $table->string('patient_phone')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('fees')->default(0);
            $table->text('visit_reason')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1 => appointment, 2 => visit, 3 => from clinic');
            $table->boolean('user_rated')->default(0)->comment('0 => no, 1 => yes');
            $table->boolean('doctor_rated')->default(0)->comment('0 => no, 1 => yes');
            $table->boolean('user_canceled')->default(0)->comment('0 => no, 1 => yes');
            $table->boolean('doctor_canceled')->default(0)->comment('0 => no, 1 => yes');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_family_id')->nullable();
            $table->unsignedBigInteger('user_address_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_family_id')->references('id')->on('user_families')->onDelete('cascade');
            $table->foreign('user_address_id')->references('id')->on('user_addresses')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
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
        Schema::dropIfExists('appointments');
    }
}
