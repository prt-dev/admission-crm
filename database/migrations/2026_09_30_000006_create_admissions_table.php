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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique();
            $table->string('registration_number')->nullable()->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index(); // code-level relation with users (student login account)
            $table->unsignedBigInteger('lead_id')->nullable()->index(); // code-level relation with leads (if converted)
            $table->unsignedBigInteger('course_id')->index(); // code-level relation with courses
            $table->unsignedBigInteger('batch_id')->nullable()->index(); // code-level relation with batches
            
            // Student personal and demographic details
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->index();
            $table->string('phone')->index();
            $table->string('alternate_phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 20)->nullable();
            $table->string('qualification')->nullable();

            // Admission & Fee details
            $table->date('admission_date');
            $table->decimal('course_fee', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('final_fee', 10, 2)->default(0.00);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('due_amount', 10, 2)->default(0.00);
            $table->unsignedTinyInteger('payment_status')->default(1)->index(); // 1=Pending, 2=Partial, 3=Paid
            $table->unsignedTinyInteger('status')->default(1)->index(); // 1=Confirmed/Active, 2=Pending Verification, 3=Cancelled, 4=Completed/Graduated
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('admitted_by')->nullable()->index(); // code-level relation with users (counselor/admin)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
