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
        Schema::table('user_logs', function (Blueprint $table) {
            $table->boolean('success_flag')->default(true)->after('type'); // true/false
            $table->text('memo')->nullable()->after('success_flag'); // NULLあり文字列
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_logs', function (Blueprint $table) {
            $table->dropColumn(['success_flag', 'memo']);
        });
    }
};
