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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->string('commercial_register_number')->unique()->nullable();
            $table->date('commercial_register_start_date')->nullable();
            $table->date('commercial_register_end_date')->nullable();
            $table->date('commercial_register_date')->nullable(); // Extracted from PDF
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->boolean('is_qualified')->default(false);
            $table->date('qualification_expiry_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
