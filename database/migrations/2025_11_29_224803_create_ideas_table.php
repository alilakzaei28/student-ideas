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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            
            // ستون مربوط به کاربر ثبت کننده (اتصال به جدول users)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // **ستون جدید: مربوط به دسته‌بندی ایده (اتصال به جدول categories)**
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            $table->string('title');       // عنوان ایده
            $table->text('description');   // توضیحات ایده
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};