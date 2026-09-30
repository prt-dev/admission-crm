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
        Schema::create('lead_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->index(); // code-level relationship with leads
            $table->unsignedBigInteger('user_id')->nullable()->index(); // code-level relationship with users (agent who made follow-up)
            $table->string('type', 50)->default('call'); // call, email, meeting, whatsapp, note
            $table->text('remarks');
            $table->dateTime('scheduled_at')->nullable()->index();
            $table->dateTime('completed_at')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->index(); // 1=Pending, 2=Completed, 3=Cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_follow_ups');
    }
};
