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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('course_id')->index(); // code-level relation with courses
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('timing')->nullable(); // e.g., "09:00 AM - 01:00 PM"
            $table->unsignedInteger('capacity')->default(30);
            $table->unsignedTinyInteger('status')->default(1)->index(); // 1=Upcoming, 2=Ongoing, 3=Completed, 4=Cancelled
            $table->unsignedBigInteger('instructor_id')->nullable()->index(); // code-level relation with users
            $table->unsignedBigInteger('created_by')->nullable()->index(); // code-level relation with users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
