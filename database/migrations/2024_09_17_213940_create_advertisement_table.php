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
        Schema::create('advertisement', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                             // 広告名
            $table->unsignedBigInteger('aff_id');                               // affiliatesテーブルと紐づく広告ID
            $table->text('memo')->nullable();                                   // メモ
            $table->integer('click_cnt')->default(0);                           // クリック回数
            $table->string('type',20)->comment('表示場所');                     // 表示場所（数値で管理）
            $table->string('sdate', 5)->nullable()->comment('MM-DD');           // 掲載開始日時（年は考慮しない）
            $table->tinyInteger('days')->nullable()->comment('掲載日数');       // 掲載日数
            $table->tinyInteger('age')->nullable()->comment('10刻み');          // 掲載対象年齢（10刻み）
            $table->tinyInteger('gender')->nullable()->comment('0:男性,1:女性');//掲載対象性別
            $table->tinyInteger('priority')->default(0)->comment('優先度');     // 優先
            $table->boolean('disp_flag')->default(false);                       // 表示有無
            $table->timestamps();

            // 外部キー制約
            $table->foreign('aff_id', 'advertisement-affiliates-id')->references('id')->on('affiliates')->onDelete('cascade');
            // インデックスを作成
            $table->index('type');
            $table->index('aff_id');
            $table->index('priority');
            $table->index('click_cnt');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisement');
    }
};
