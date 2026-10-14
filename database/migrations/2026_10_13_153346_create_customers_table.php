<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 担当（登録）ユーザー
            $table->string('name');                       // 顧客名
            $table->string('company_name')->nullable();   // 会社名
            $table->string('email')->nullable();          // メールアドレス
            $table->string('phone', 20)->nullable();      // 電話番号
            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};