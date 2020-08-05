<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->float('amount');
            $table->string('package');
            $table->integer('billing_interval')->default(30);
            $table->integer('trial_days')->default(0);
            $table->string('customer_id')->nullable();
            $table->string('payment_method');
            $table->string('transaction_id');
            $table->tinyInteger('is_refunded')->default(0);
            $table->tinyInteger('is_success')->default(1);
            $table->tinyInteger('is_subscription')->default(0);
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
        Schema::dropIfExists('payments');
    }
}
