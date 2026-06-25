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
        Schema::create('software_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();

            // relasi users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // relasi units
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');

            // relasi applications
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');

            $table->enum('request_type', ['new_feature', 'modification', 'bug_fix']);
            $table->string('title');
            $table->text('description');
            $table->text('business_impact');
            $table->date('target_used_date');
            $table->string('attachment_path')->nullable();

            $table->enum('status', ['submitted', 'analysis_scheduled', 'approved', 'rejected'])->default('submitted');
            $table->text('meeting_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software_requests');
    }
};
