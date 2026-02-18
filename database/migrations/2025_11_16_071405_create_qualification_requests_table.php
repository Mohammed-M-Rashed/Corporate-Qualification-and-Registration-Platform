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
        Schema::create('qualification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('request_number')->unique();
            $table->enum('status', ['new', 'under_review', 'approved', 'rejected'])->default('new');
            $table->enum('current_review_stage', ['legal', 'technical', 'financial', 'chairman', 'completed'])->default('legal');
            $table->enum('legal_review_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('technical_review_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('financial_review_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->foreignId('legal_reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('technical_reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('financial_reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('legal_reviewed_at')->nullable();
            $table->timestamp('technical_reviewed_at')->nullable();
            $table->timestamp('financial_reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualification_requests');
    }
};
