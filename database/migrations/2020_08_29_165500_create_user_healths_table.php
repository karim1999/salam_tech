<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHealthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_healths', function (Blueprint $table) {
            $table->id();
            $table->integer('height')->default(0);
            $table->integer('weight')->default(0);
            $table->string('blood_pressure')->nullable();
            $table->string('sugar_level')->nullable();
            $table->string('blood_type')->nullable();
            $table->double('muscle_mass')->default(0.0);
            $table->string('metabolism')->nullable();
            $table->text('genetic_history')->nullable();
            $table->text('illness_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('prescription')->nullable();
            $table->text('operations')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_healths');
    }
}
