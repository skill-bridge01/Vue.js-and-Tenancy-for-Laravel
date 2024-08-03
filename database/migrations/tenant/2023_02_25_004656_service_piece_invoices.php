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
        Schema::create('service_piece_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id')->nullable();
            $table->bigInteger('service_piece_id')->unsigned()->nullable();
            $table->integer('number_of_piece')->nullable();
            $table->float("total_price")->nullable(true);
            $table->text("dtl")->nullable(true);
            $table->bigInteger('discount')->unsigned();
            $table->string("note")->nullable();
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
        Schema::table('service_piece_invoices', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
