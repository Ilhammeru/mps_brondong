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
        Schema::create('menstruation_leave', function (Blueprint $table) {
            $table->id();
            $table->string('leave_code');
            $table->integer('employee_id');
            $table->timestamp('leave_date_time')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('checked_by')->nullable();
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
        Schema::dropIfExists('menstruation_leave');
    }
};
