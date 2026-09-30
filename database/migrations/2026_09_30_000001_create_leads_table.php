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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->index();
            $table->string('alternate_phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('source')->nullable()->index(); // website, referral, walk-in, social_media, etc.
            $table->string('course_interested')->nullable()->index();
            $table->unsignedBigInteger('assigned_to')->nullable()->index(); // code-level relationship with users
            $table->unsignedTinyInteger('status')->default(1)->index(); // 1=New, 2=Contacted, 3=Follow-up, 4=Converted, 5=Closed/Lost, 6=Junk
            $table->text('notes')->nullable();
            $table->dateTime('next_follow_up_at')->nullable()->index();
            $table->dateTime('converted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index(); // code-level relationship with users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
