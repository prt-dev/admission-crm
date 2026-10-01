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
        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "2025-2026", "2026-2027", "Session 2026"
            $table->string('code')->unique(); // e.g. "SESS-2025-26", "SESS-2026-27"
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false)->index(); // True if current active academic session
            $table->unsignedTinyInteger('status')->default(1)->index(); // 1=Upcoming, 2=Active/Ongoing, 3=Completed, 4=Archived/Inactive
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index(); // code-level relation with users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_sessions');
    }
};
