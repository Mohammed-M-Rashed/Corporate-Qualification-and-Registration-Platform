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
        Schema::table('qualification_requests', function (Blueprint $table) {
            // إزالة الحقول القديمة إذا كانت موجودة
            $columnsToDrop = [];
            if (Schema::hasColumn('qualification_requests', 'committee_review_status')) {
                $columnsToDrop[] = 'committee_review_status';
            }
            if (Schema::hasColumn('qualification_requests', 'all_members_reviewed')) {
                $columnsToDrop[] = 'all_members_reviewed';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
            
            // إزالة الحقول القديمة الأخرى إذا كانت موجودة
            $oldColumnsToDrop = [];
            if (Schema::hasColumn('qualification_requests', 'rejection_reason')) {
                $oldColumnsToDrop[] = 'rejection_reason';
            }
            if (Schema::hasColumn('qualification_requests', 'reviewed_by')) {
                // حذف foreign key أولاً
                $table->dropForeign(['reviewed_by']);
                $oldColumnsToDrop[] = 'reviewed_by';
            }
            if (Schema::hasColumn('qualification_requests', 'reviewed_at')) {
                $oldColumnsToDrop[] = 'reviewed_at';
            }
            if (!empty($oldColumnsToDrop)) {
                $table->dropColumn($oldColumnsToDrop);
            }
            
            // إضافة مرحلة المراجعة الحالية
            $table->enum('current_review_stage', ['legal', 'technical', 'financial', 'chairman', 'completed'])
                ->default('legal')
                ->after('status');
            
            // إضافة حالة مراجعة كل مرحلة
            $table->enum('legal_review_status', ['pending', 'approved', 'rejected'])->nullable()->after('current_review_stage');
            $table->enum('technical_review_status', ['pending', 'approved', 'rejected'])->nullable()->after('legal_review_status');
            $table->enum('financial_review_status', ['pending', 'approved', 'rejected'])->nullable()->after('technical_review_status');
            
            // إضافة من قام بالمراجعة لكل مرحلة
            $table->foreignId('legal_reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('financial_review_status');
            $table->foreignId('technical_reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('legal_reviewed_by');
            $table->foreignId('financial_reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('technical_reviewed_by');
            
            // إضافة تاريخ المراجعة لكل مرحلة
            $table->timestamp('legal_reviewed_at')->nullable()->after('financial_reviewed_by');
            $table->timestamp('technical_reviewed_at')->nullable()->after('legal_reviewed_at');
            $table->timestamp('financial_reviewed_at')->nullable()->after('technical_reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualification_requests', function (Blueprint $table) {
            // إزالة الحقول الجديدة
            $table->dropForeign(['legal_reviewed_by']);
            $table->dropForeign(['technical_reviewed_by']);
            $table->dropForeign(['financial_reviewed_by']);
            $table->dropColumn([
                'current_review_stage',
                'legal_review_status',
                'technical_review_status',
                'financial_review_status',
                'legal_reviewed_by',
                'technical_reviewed_by',
                'financial_reviewed_by',
                'legal_reviewed_at',
                'technical_reviewed_at',
                'financial_reviewed_at'
            ]);
            
            // إعادة الحقول القديمة
            $table->json('committee_review_status')->nullable();
            $table->boolean('all_members_reviewed')->default(false);
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
        });
    }
};
