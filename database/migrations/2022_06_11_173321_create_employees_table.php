<?php

use Carbon\Carbon;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('employee_id');
            $table->string('name');
            $table->string('aliases')->nullable();
            $table->string('email')->email();
            $table->string('nik');
            $table->string('gender', 1);
            $table->date('birth_date');
            $table->string('address');
            $table->integer('province_id');
            $table->string('regency_id');
            $table->string('district_id');
            $table->string('village_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_whatsapp')->default(FALSE);
            $table->string('primary_school')->nullable();
            $table->string('primary_school_graduate')->nullable();
            $table->float('primary_school_gpa')->nullable();
            $table->string('junior_high_school')->nullable();
            $table->string('junior_high_school_graduate')->nullable();
            $table->float('junior_high_school_gpa')->nullable();
            $table->string('high_school')->nullable();
            $table->string('high_school_graduate')->nullable();
            $table->float('high_school_gpa')->nullable();
            $table->string('university')->nullable();
            $table->string('university_graduate')->nullable();
            $table->float('university_gpa')->nullable();
            $table->string('work_experience_name_1')->nullable();
            $table->string('work_experience_position_1')->nullable();
            $table->string('work_experience_in_1')->nullable();
            $table->string('work_experience_out_1')->nullable();
            $table->string('work_experience_name_2')->nullable();
            $table->string('work_experience_position_2')->nullable();
            $table->string('work_experience_in_2')->nullable();
            $table->string('work_experience_out_2')->nullable();
            $table->string('work_experience_name_3')->nullable();
            $table->string('work_experience_position_3')->nullable();
            $table->string('work_experience_in_3')->nullable();
            $table->string('work_experience_out_3')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_job')->nullable();
            $table->string('mother_job')->nullable();
            $table->text('father_address')->nullable();
            $table->text('mother_address')->nullable();
            $table->text('emergency_contact_name_1')->nullable();
            $table->text('emergency_contact_number_1')->nullable();
            $table->text('emergency_contact_siblings_1')->nullable();
            $table->text('emergency_contact_name_2')->nullable();
            $table->text('emergency_contact_number_2')->nullable();
            $table->text('emergency_contact_siblings_2')->nullable();
            $table->tinyInteger('current_vaccine_level')->default(0); // tingkat vaksin terakhir 1/2/3/booster
            $table->tinyInteger('employee_status')->nullable(); // this will be for 'contract' or 'permanent value'
            $table->date('date_in_contract')->nullable();
            $table->date('date_in_permanent')->nullable();
            $table->text('contract_doc')->nullable();
            $table->text('permanent_doc')->nullable();
            $table->integer('division_id');
            $table->integer('position_id');
            $table->string('bank_account_name');
            $table->string('bank_account_number');
            $table->string('bank_name');
            $table->string('bpjs_ketenagakerjaan_number')->nullable();
            $table->string('bpjs_kesehatan_number')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
