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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('username');
            $table->text('password');
            $table->integer('division_id')->nullable();
            $table->integer('position_id')->nullable();
            $table->date('start_working_date')->nullable();
            $table->tinyInteger('start_working_month')->nullable();
            $table->text('photo')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_active')->default(TRUE);
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
        Schema::dropIfExists('users');
    }
};
