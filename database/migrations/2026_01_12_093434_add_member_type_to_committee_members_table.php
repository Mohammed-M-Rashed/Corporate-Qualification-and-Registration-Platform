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
            $table->enum('member_type', ['legal', 'technical', 'financial'])->nullable()->after('user_id');
            
            // إضافة unique constraint للتأكد من وجود عضو واحد فقط من كل نوع في اللجنة
            $table->unique(['committee_id', 'member_type'], 'committee_member_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('committee_members', function (Blueprint $table) {
            $table->dropUnique('committee_member_type_unique');
            $table->dropColumn('member_type');
        });
    }
};
