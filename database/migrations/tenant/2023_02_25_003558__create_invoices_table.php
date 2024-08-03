<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string("customer_name")->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('discount')->unsigned();
            $table->string("note")->nullable();
            $table->bigInteger('daily_id')->unsigned();
            $table->string("status")->default("1");
            $table->string("payment_method")->default("cash"); // cash or card
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        //
        Schema::dropIfExists('service_piece_invoices');
        Schema::dropIfExists('invoices');
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
