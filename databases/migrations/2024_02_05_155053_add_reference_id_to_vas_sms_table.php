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
        Schema::table('vas_sms', function (Blueprint $table) {
            $table->string('reference_id')->after('id')->nullable();
            $table->json('json_request')->after('reference_id')->nullable();
            $table->string('response')->after('json_request')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vas_sms', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->dropColumn('json_request');
            $table->dropColumn('response');
        });
    }
};
