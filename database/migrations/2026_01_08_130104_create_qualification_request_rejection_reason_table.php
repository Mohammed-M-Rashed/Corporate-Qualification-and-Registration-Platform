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
        Schema::dropIfExists('qualification_request_rejection_reason');
        
        Schema::create('qualification_request_rejection_reason', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qualification_request_id');
            $table->unsignedBigInteger('rejection_reason_id');
            $table->timestamps();
            
            $table->foreign('qualification_request_id', 'req_rej_req_id_fk')
                ->references('id')
                ->on('qualification_requests')
                ->onDelete('cascade');
                
            $table->foreign('rejection_reason_id', 'req_rej_reason_id_fk')
                ->references('id')
                ->on('rejection_reasons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualification_request_rejection_reason');
    }
};
