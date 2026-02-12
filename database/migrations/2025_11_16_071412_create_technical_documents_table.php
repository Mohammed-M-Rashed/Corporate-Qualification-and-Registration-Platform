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
        Schema::create('technical_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qualification_request_id')->constrained()->onDelete('cascade');
            $table->enum('experience_level', ['0-3', '4-10', 'more_than_10']);
            $table->enum('document_type', [
                'completed_projects',
                'technical_staff',
                'quality_certificates'
            ]);
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_documents');
    }
};
