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
        Schema::create('virtual_remotes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('kHz')->default(38);
            $table->unsignedBigInteger('admin_user_id');
            $table->string('remote_name');
            $table->foreignId('blade_id'); // Bladeテンプレート名
            $table->timestamps();
            
            // 外部キー制約
            $table->foreign('admin_user_id', 'virtual_remotes-users-id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_remotes');
    }
};
