<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrovinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urovin', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('reka_id')->unsigned();
            $table->foreign('reka_id')->references('id')->on('reki')->onDelete('cascade');
            $table->string('reka_name',86);
            $table->integer('urovin');
            $table->string('delta',32);
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
        Schema::dropIfExists('urovin');
    }
}
