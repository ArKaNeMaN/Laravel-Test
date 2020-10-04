<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Currency', function (Blueprint $table) {
            $table->unsignedBigInteger('Id')->autoIncrement();
            $table->string('ShortName', 8);
            $table->string('Name', 64);
            $table->unique('ShortName');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::table('Courses', function (Blueprint $table) {
        //    $table->dropForeign('courses_currencyid_foreign');
        //    $table->dropColumn('CurrencyId');
        //});
        Schema::dropIfExists('Currency');
    }
}
