<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('semester_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_type_id');
            $table->unsignedBigInteger('term_id');
        
            $table->decimal('tuition_fee', 10, 2)->nullable();
            $table->decimal('admission_fee', 10, 2)->nullable();
            $table->decimal('university_registration_fee', 10, 2)->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->decimal('medical_checkup', 10, 2)->nullable();
            $table->decimal('semester_enrollment_fee', 10, 2)->nullable();
            $table->decimal('examination_tution_fee', 10, 2)->nullable();
            $table->decimal('co_curricular_activities_fee', 10, 2)->nullable();
            $table->decimal('hostel_fee', 10, 2)->nullable();
            $table->decimal('pharmacy_council_reg_fee', 10, 2)->nullable();
            $table->decimal('clinical_charge', 10, 2)->nullable();
            $table->decimal('library_fee', 10, 2)->nullable();
            $table->decimal('migration_fee', 10, 2)->nullable();
            $table->decimal('document_verification_fee', 10, 2)->nullable();
            $table->decimal('application_prospectus_fee', 10, 2)->nullable();
            $table->decimal('degree_convocation_fee', 10, 2)->nullable();
            $table->decimal('research_thesis', 10, 2)->nullable();
            $table->decimal('special_discount', 10, 2)->nullable();
        
            $table->boolean('status')->default(1);
            $table->timestamps();
        
            $table->foreign('fee_type_id')->references('id')->on('fee_types')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_fees');
    }
};
