<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_messages', function (Blueprint $table) {
            $table->id();
            $table->string('msg')->nullable();
            $table->boolean('seen')->default(0)->comment('0 => no, 1 => yes');
            $table->tinyInteger('sender')->default(1)->comment('1 => doctor, 2 => user');
            $table->unsignedBigInteger('consultation_id')->nullable();

            $table->foreign('consultation_id')->references('id')->on('consultations')->onDelete('cascade');
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
        Schema::dropIfExists('consultation_messages');
    }
}
