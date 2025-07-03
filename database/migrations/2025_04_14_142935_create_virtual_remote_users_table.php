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
        Schema::create('virtual_remote_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remote_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('admin_flag')->default(false); // リモコンの編集権限を持つか
            $table->boolean('stop_flag')->default(false); // リモコンを使用停止されているか
            $table->timestamps();

            // 外部キー制約
            $table->foreign('remote_id', 'virtual_remote_users-virtual_remotes-id')->references('id')->on('virtual_remotes')->onDelete('cascade');
            $table->foreign('user_id', 'virtual_remote_users-users-id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['remote_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_remote_users');
    }
};
