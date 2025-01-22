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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('release_flag')->default(0)->after('admin_flag')->comment('公開状態');
            $table->boolean('mail_flag')->default(0)->after('release_flag')->comment('メール送信拒否');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('release_flag');
            $table->dropColumn('mail_flag');
        });
    }
};
