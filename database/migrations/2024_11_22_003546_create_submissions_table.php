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
        Schema::create('submissions', function (Blueprint $table) {
        $table->id(); // رقم الطلب
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم الذي أرسل الطلب
        $table->foreignId('company_id')->constrained()->onDelete('cascade'); // الشركة التي أُرسل إليها الطلب
        $table->string('email'); // الإيميل المستخدم للإرسال
        $table->string('cv'); // رابط السيرة الذاتية
        $table->text('description')->nullable(); // وصف الطلب
        $table->string('position'); // نوع الوظيفة المطلوبة
        $table->boolean('is_sent')->default(false); // حالة الإرسال
        $table->timestamps(); // تاريخ الإنشاء والتحديث
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
