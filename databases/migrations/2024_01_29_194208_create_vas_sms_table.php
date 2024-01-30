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
        Schema::create('vas_sms', function (Blueprint $table) {
            $table->id();
            $table->string('requestId')->nullable();
            $table->string('requestTimeStamp')->nullable();
            $table->string('channel')->nullable();
            $table->string('operation')->nullable();
            $table->string('traceID')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('cp_id')->nullable();
            $table->string('correlator_id')->nullable();
            $table->string('description')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('type')->nullable();
            $table->string('campaign_id')->nullable();
            $table->json('json_result')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vas_sms');
    }
};
