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
        Schema::create('vaccine', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->tinyInteger('next_period_1')->nullable();
            $table->tinyInteger('next_period_2')->nullable();
            $table->tinyInteger('next_period_3')->nullable();
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
        Schema::dropIfExists('vaccine');
    }
};
