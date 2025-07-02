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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('reg_no')->unique();
            $table->unsignedBigInteger('semester_id');
            $table->integer('credit_hrs');
            $table->decimal('gpa', 4, 2);
            $table->integer('hssc_marks');
            $table->unsignedBigInteger('term_id');
            $table->boolean('status')->default(1);
            $table->timestamps();
        
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
