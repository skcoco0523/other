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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('line_id')->unique()->nullable();
            $table->string('provider',10)->nullable();
            $table->boolean('admin_flag')->default(false);
            $table->string('name');
            $table->tinyInteger('gender')->nullable()->comment('0:男性,1:女性');
            $table->date('birthdate')->nullable();  // NULL を許可
            $table->string('prefectures',10)->nullable()->comment('都道府県');
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('friend_code', 8)->nullable()->index('friend_code');
            $table->boolean('release_flag')->default(0)->comment('公開状態');
            $table->boolean('mail_flag')->default(0)->comment('メール送信拒否');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
