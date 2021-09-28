<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->tinyInteger('display')->default(1);
            $table->date('start_date');
            $table->date('expire_date');
            $table->string('start_time')->nulllable();
            $table->string('expire_time')->nullable();
            $table->float('min_spend', 8, 2)->default(0);
            $table->float('max_discount', 8, 2)->nullable();
            $table->float('discount_percentage',8,2)->nullable();
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_coupons');
    }
}
