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
        Schema::create('service_piece', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('piece_id')->nullable();
            $table->float("price");
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
        Schema::table('service_piece', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        //
       
    }
};
