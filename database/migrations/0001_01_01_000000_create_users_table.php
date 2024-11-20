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
            $table->id(); // رقم المستخدم (Primary Key)
            $table->string('name'); // اسم المستخدم
            $table->string('email')->unique(); // الإيميل
            $table->string('facebook')->nullable(); // حساب فيسبوك (اختياري)
            $table->string('instagram')->nullable(); // حساب إنستاغرام (اختياري)
            $table->string('linkedin')->nullable(); // حساب لينكد إن (اختياري)
            $table->string('phone_number')->nullable(); // رقم الهاتف (اختياري)
            $table->string('address')->nullable(); // العنوان (اختياري)
            $table->string('cv_path')->nullable(); // رابط ملف السيرة الذاتية (اختياري)
            $table->string('desired_position'); // البوزشن المرغوب
            $table->text('description')->nullable(); // وصف إضافي (اختياري)
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
