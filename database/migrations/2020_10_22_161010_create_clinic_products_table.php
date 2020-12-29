<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('id_product')->nullable();
            $table->string('unit_measure')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('id_supplier')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('min_stock_quantity')->default(0);
            $table->date('expire_date')->nullable();
            $table->date('min_stock_expire_date')->nullable();
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
        Schema::dropIfExists('clinic_products');
    }
}
