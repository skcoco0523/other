<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('device_id')->unique();
            $table->string('os');
            $table->string('browser');
            $table->string('endpoint')->comment('エンドポイントURL');
            $table->string('public_key')->comment('公開鍵');
            $table->string('auth_token')->comment('認証情報');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id', 'user_devices-users-id')->references('id')->on('users')->onDelete('cascade');
            // インデックスの設定
            $table->index('user_id');
        });
    }


    public function down()
    {
        Schema::dropIfExists('user_devices');
    }

};
