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
        Schema::create('virtual_remote_blades', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('kind');
            $table->string('company_name')->nullable();
            $table->string('product_name')->nullable();
            $table->string('blade_name');
            $table->boolean('test_flag')->default(true);
            $table->timestamps();
            
            // 外部キー制約
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_remote_blades');
    }
};
