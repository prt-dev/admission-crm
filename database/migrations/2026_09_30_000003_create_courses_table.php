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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('duration')->nullable(); // e.g., "6 Months", "1 Year"
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->unsignedTinyInteger('status')->default(1)->index(); // 1=Active, 2=Inactive
            $table->unsignedBigInteger('created_by')->nullable()->index(); // code-level relation with users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
