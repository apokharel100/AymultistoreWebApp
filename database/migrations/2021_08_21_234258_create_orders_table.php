<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->integer('customer_id')->default(0);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->longText('billing_details');
            $table->longText('shipping_details');
            $table->longText('coupon_details')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->float('delivery_charge')->default(0);
            $table->float('total_price');
            $table->tinyInteger('payment_status')->default(0);
            $table->tinyInteger('payment_method')->default(1);
            $table->tinyInteger('delivery_method')->default(0);
            $table->string('payment_id')->nullable();
            $table->datetime('paid_date')->nullable();
            $table->longText('order_json');
            $table->text('additional_message')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
