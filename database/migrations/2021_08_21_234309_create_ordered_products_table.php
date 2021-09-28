<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('product_title');
            $table->float('product_price');
            $table->foreignId('color_id');
            $table->string('color_name')->nullable();
            $table->string('color_code')->nullable();
            $table->foreignId('size_id');
            $table->string('size_name')->nullable();
            $table->integer('quantity');
            $table->float('sub_total');
            $table->tinyInteger('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('ordered_products');
    }
}
