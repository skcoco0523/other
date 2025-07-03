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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address')->nullable(); // IPアドレス
            $table->string('type'); // ログタイプ（string型）
            $table->timestamp('created_at')->useCurrent(); // タイムスタンプ
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('user_id');  // user_idにインデックスを作成
            $table->index('type');  // typeにインデックスを作成
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
