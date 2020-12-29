<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('id_employee')->nullable();
            $table->string('position')->nullable();
            $table->double('net_salary')->nullable();
            $table->double('gross_salary')->nullable();
            $table->text('docs_checklist')->nullable();
            $table->tinyInteger('gender')->default(1)->comment('1 => male, 2 => female');
            $table->unsignedBigInteger('clinic_id')->nullable();

            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
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
        Schema::dropIfExists('clinic_employees');
    }
}
