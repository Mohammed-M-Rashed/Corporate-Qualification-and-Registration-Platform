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
        Schema::table('committee_members', function (Blueprint $table) {
            // إزالة القيد الفريد أولاً
            $table->dropUnique('committee_member_type_unique');
            // ثم إزالة العمود
            $table->dropColumn('member_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('committee_members', function (Blueprint $table) {
            $table->enum('member_type', ['legal', 'technical', 'financial'])
                ->after('user_id');
            $table->unique(['committee_id', 'member_type'], 'committee_member_type_unique');
        });
    }
};
