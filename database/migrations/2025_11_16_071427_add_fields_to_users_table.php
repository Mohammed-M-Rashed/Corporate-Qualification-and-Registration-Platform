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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('member_type', ['legal', 'technical', 'financial'])
                ->nullable()
                ->after('email_verified_at')
                ->comment('نوع العضو: قانوني، فني، أو مالي');
            $table->foreignId('committee_id')->nullable()->after('member_type')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['committee_id']);
            $table->dropColumn(['member_type', 'committee_id']);
        });
    }
};
