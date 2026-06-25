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
        Schema::table('software_requests', function (Blueprint $table) {
            Schema::table('software_requests', function (Blueprint $table) {
                $table->tinyInteger('rating')->nullable()->after('meeting_notes');
                $table->text('rating_feedback')->nullable()->after('rating');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software_requests', function (Blueprint $table) {
            $table->dropColumn(['rating', 'rating_feedback']);
        });
    }
};
