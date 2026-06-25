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
        Schema::create('development_projects', function (Blueprint $table) {
            $table->id();

            // relasi software requests
            $table->unsignedBigInteger('software_request_id');
            $table->foreign('software_request_id')->references('id')->on('software_requests')->onDelete('cascade');

            // relasi programmers
            $table->unsignedBigInteger('programmer_id');
            $table->foreign('programmer_id')->references('id')->on('programmers')->onDelete('cascade');

            $table->enum('t_shirt_size', ['S', 'M', 'L', 'XL']);
            $table->string('phase_title');
            $table->integer('story_points');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->enum('project_status', [
                'waiting',
                'in_development',
                'suspended',
                'close_suspended',
                'uat_testing',
                'ready_for_production',
                'production',
                'closed',
            ])->default('waiting');

            $table->enum('uat_status', ['pending', ['approved', 'rejected']])->nullable();
            $table->text('uat_feedback')->nullable();

            $table->boolean('is_active_load')->default(false);

            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_projects');
    }
};
