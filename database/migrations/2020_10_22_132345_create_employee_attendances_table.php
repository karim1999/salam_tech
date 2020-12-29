<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 => show up, 2 => late, 3 => not show up');
            $table->string('delay_time')->nullable();
            $table->double('deduction')->nullable();
            $table->boolean('paid_leave')->nullable()->comment('0 => false, 1 => true');
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->foreign('employee_id')->references('id')->on('clinic_employees')->onDelete('cascade');
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
        Schema::dropIfExists('employee_attendances');
    }
}
