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
        Schema::create('iot_device_signals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('remote_id');
            $table->integer('button_num');
            $table->string('category_name', 100)->comment('テレビ,照明,エアコン,etc');                       // デバイスのタイプ
            $table->string('signal_name', 100);
            $table->text('signal_data');
            //$table->timestamps();
            
            $table->foreign('device_id', 'iot_device_signals-iot_devicese-id')->references('id')->on('iot_devices')->onDelete('cascade');
            $table->foreign('remote_id', 'iot_device_signals-virtual_remotes-id')->references('id')->on('virtual_remotes')->onDelete('cascade');

            $table->unique(['device_id', 'remote_id', 'button_num'], 'iot_signals_device_remote_button_unique');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_device_signals');
    }
};
