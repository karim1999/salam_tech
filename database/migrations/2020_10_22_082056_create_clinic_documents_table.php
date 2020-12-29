<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_documents', function (Blueprint $table) {
            $table->id();
            $table->string('registration')->nullable();
            $table->string('license')->nullable();
            $table->string('tax_id')->nullable();
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
        Schema::dropIfExists('clinic_documents');
    }
}
