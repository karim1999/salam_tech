<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('pharmacy_distance')->default(0);
            $table->integer('lab_distance')->default(0);
            $table->integer('clinic_distance')->default(0);
            $table->integer('doctor_distance')->default(0);
            $table->integer('map_distance')->default(0);
            $table->integer('rate_points')->default(0);
            $table->longText('user_terms_ar')->nullable();
            $table->longText('user_terms_en')->nullable();
            $table->longText('doctor_terms_ar')->nullable();
            $table->longText('doctor_terms_en')->nullable();
            $table->longText('clinic_terms_ar')->nullable();
            $table->longText('clinic_terms_en')->nullable();
            $table->longText('user_policy_ar')->nullable();
            $table->longText('user_policy_en')->nullable();
            $table->longText('user_help_ar')->nullable();
            $table->longText('user_help_en')->nullable();
            $table->longText('doctor_policy_ar')->nullable();
            $table->longText('doctor_policy_en')->nullable();
            $table->longText('clinic_policy_ar')->nullable();
            $table->longText('clinic_policy_en')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
