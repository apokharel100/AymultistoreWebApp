<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('brand_id')->default(0);
            $table->string('sku');
            $table->string('image');
            $table->float('price');
            $table->float('discounted_price')->default(0);
            $table->boolean('display')->default(0);
            $table->boolean('featured')->default(0);
            $table->tinyInteger('variation_type')->default(1);
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->boolean('stock_status')->default(1);
            $table->integer('stock_count')->nullable();
            $table->string('unit_name')->nullable();
            $table->text('tags')->nullable();
            $table->bigInteger('views')->default(0);
            $table->integer('order_item');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
