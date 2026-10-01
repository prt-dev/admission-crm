<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('batches', 'course_id')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->dropColumn('course_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('batches', 'course_id')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->unsignedBigInteger('course_id')->nullable()->index();
            });
        }
    }
};
