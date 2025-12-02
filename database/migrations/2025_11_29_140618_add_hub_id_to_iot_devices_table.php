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
        Schema::table('iot_devices', function (Blueprint $table) {
            // hub_id カラムを追加（NULL許容）
            $table->unsignedBigInteger('hub_id')->nullable()->after('id')->index();
            // 外部キー制約
            $table->foreign('hub_id')->references('id')->on('iot_devices')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iot_devices', function (Blueprint $table) {
            $table->dropForeign(['hub_id']);    // 外部キー削除
            $table->dropIndex(['hub_id']);      // インデックス削除
            $table->dropColumn('hub_id');       // カラム削除
        });
    }
};
