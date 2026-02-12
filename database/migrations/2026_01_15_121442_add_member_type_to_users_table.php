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
        });
        
        // نقل البيانات من committee_members إلى users
        // نأخذ أول نوع عضو لكل مستخدم
        DB::statement("
            UPDATE users u
            INNER JOIN (
                SELECT user_id, MIN(member_type) as member_type
                FROM committee_members
                GROUP BY user_id
            ) cm ON u.id = cm.user_id
            SET u.member_type = cm.member_type
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('member_type');
        });
    }
};
