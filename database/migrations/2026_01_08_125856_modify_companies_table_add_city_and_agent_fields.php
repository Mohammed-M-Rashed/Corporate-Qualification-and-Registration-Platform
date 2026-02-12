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
        Schema::table('companies', function (Blueprint $table) {
            // إزالة registration_number
            $table->dropUnique(['registration_number']);
            $table->dropColumn('registration_number');
            
            // إضافة city_id
            $table->foreignId('city_id')->nullable()->after('name')->constrained('cities')->onDelete('set null');
            
            // إضافة حقول الوكيل
            $table->boolean('is_agent')->default(false)->after('address');
            $table->string('agent_document_path')->nullable()->after('is_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // إعادة registration_number
            $table->string('registration_number')->unique()->after('name');
            
            // إزالة city_id
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
            
            // إزالة حقول الوكيل
            $table->dropColumn(['is_agent', 'agent_document_path']);
        });
    }
};
