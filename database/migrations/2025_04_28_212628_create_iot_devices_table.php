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
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->id();
            $table->string('mac_addr', 32)->unique();                           // デバイスのMACアドレス
            $table->string('name')->nullable();                                 // デバイスの登録名
            $table->tinyInteger('ver')->default(1);                             // デバイスのバージョン
            $table->integer('type');                                            // デバイスのタイプ
            $table->integer('pincode')->nullable()->unique();                // デバイス追加時のPINコード
            $table->unsignedBigInteger('admin_user_id')->nullable();            // デバイスの所有者
            $table->timestamps();
            
            // 外部キー制約
            $table->foreign('admin_user_id', 'iot_devices-users-id')->references('id')->on('users')->onDelete('cascade')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_devices');
    }
};
