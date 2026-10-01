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
        Schema::create('batch_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id')->index(); // code-level relation with batches
            $table->unsignedBigInteger('course_id')->index(); // code-level relation with courses
            $table->timestamps();

            $table->unique(['batch_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_courses');
    }
};
