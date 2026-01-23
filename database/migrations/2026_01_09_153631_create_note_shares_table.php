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
        Schema::create('note_shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->boolean('admin_flag')->default(false); // 編集権限
            $table->timestamps();

            // 外部キー制約
            $table->foreign('note_id', 'note_shares_note_id_foreign')->references('id')->on('notes')->onDelete('cascade');
            $table->foreign('user_id', 'note_shares_user_id_foreign')->references('id')->on('users')->onDelete('cascade');

            // 同じメモを同じユーザーに重複して共有させない
            $table->unique(['note_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_shares');
    }
};
