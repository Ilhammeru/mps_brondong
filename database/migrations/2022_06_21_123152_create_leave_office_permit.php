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
        Schema::create('leave_office_permit', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('ticket_code');
            $table->json('employee_id');
            $table->timestamp('leave_date_time')->nullable();
            $table->text('notes')->nullable();
            $table->integer('approved_by');
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
        Schema::dropIfExists('leave_office_permit');
    }
};
