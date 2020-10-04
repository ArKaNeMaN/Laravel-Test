<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Courses', function (Blueprint $table) {
            $table->unsignedBigInteger('Id')->autoIncrement();
            $table->unsignedBigInteger('CoinId');
            $table->unsignedBigInteger('CurrencyId');
            $table->double('Amount');
            $table->timestamp('LastUpdate')->useCurrent();

            $table->foreign('CoinId')->references('Id')->on('Coins')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('CurrencyId')->references('Id')->on('Currency')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unique(['CoinId', 'CurrencyId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Courses');
    }
}
