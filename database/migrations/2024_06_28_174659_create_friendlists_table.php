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
        Schema::create('friendlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('friend_id');
            $table->tinyInteger('status')->comment('0:保留中,1:承認済み,2:拒否');
            $table->timestamps();
    
            $table->foreign('user_id', 'friendlists-users-id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('friend_id', 'friendlists-users-id2')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendlists');
    }
};
