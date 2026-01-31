<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            
            // کلید خارجی برای ایده
            $table->foreignId('idea_id')->constrained()->onDelete('cascade');
            // **کلید خارجی برای کاربر (جدید)**
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            $table->timestamps();

            // تضمین می‌کنیم که یک کاربر فقط یک بار به یک ایده رأی دهد
            $table->unique(['idea_id', 'user_id']);
            
            // ip_address حذف شد
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};