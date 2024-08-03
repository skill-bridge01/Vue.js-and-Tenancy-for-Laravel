<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_data', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('address', 255)->unique();
            $table->string('logo', 255)->nullable();
            $table->string('commercial_number', 255)->nullable();
            $table->string('tax_number', 255)->nullable();
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
        Schema::dropIfExists('company_data');
        Schema::table('company_data', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
