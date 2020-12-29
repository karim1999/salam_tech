<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_operations', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(0);
            $table->tinyInteger('type')->default(1)->comment('1 => deposit, 2 => withdraw');
            $table->date('date')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('product_id')->references('id')->on('clinic_products')->onDelete('cascade');
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
        Schema::dropIfExists('product_operations');
    }
}
