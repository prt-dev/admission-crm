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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admission_id')->index(); // code-level relation with admissions (student)
            $table->unsignedBigInteger('batch_id')->index();     // code-level relation with batches
            $table->unsignedBigInteger('course_id')->nullable()->index(); // code-level relation with courses
            
            // Core attendance session keys
            $table->date('date')->index();                       // Date of attendance
            $table->decimal('duration', 5, 2)->default(1.00);    // Duration in hours (e.g. 2.00, 4.00, 8.00)
            $table->string('type', 10)->default('T')->index();   // Type: T (Theory), P (Practical), O (OJT / On-the-Job Training)
            $table->unsignedTinyInteger('status')->default(1)->index(); // Status: 1=Present, 2=Absent, 3=Late, 4=Half Day, 5=Leave
            
            // Additional contextual details
            $table->string('topic_covered')->nullable();         // Subject / Topic covered from curriculum
            $table->text('remarks')->nullable();                 // Any trainer / counselor notes
            $table->unsignedBigInteger('marked_by')->nullable()->index(); // code-level relation with users (trainer/admin)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
