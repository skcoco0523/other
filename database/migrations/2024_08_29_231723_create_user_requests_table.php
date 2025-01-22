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
        Schema::create('user_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('type')->comment('問い合わせ種別');
            $table->text('message')->comment('問い合わせ内容');
            $table->text('reply')->nullable()->comment('返信内容');
            $table->tinyInteger('status')->default(0)->comment('0:未対応,1:対応済み,2:対応不可');
            $table->timestamps();
            // リレーションを設定
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // インデックスを作成
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_requests');
    }
};
