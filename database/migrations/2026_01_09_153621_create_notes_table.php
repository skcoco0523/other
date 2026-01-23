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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // 作成者ID
            $table->string('title');
            $table->text('content')->nullable();
            $table->tinyInteger('color_num')->default(0);
            $table->boolean('edit_lock_flag')->default(false); // 編集権限
            $table->timestamps();

            // 外部キー制約：作成者が削除されたらメモも消す場合
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
