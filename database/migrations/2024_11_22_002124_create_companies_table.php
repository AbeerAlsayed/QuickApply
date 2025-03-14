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
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // رقم الشركة
            $table->string('name')->nullable(); // اسم الشركة
            $table->string('email')->nullable(); // بريد الشركة الإلكتروني
            $table->foreignId('country_id')->constrained()->onDelete('cascade'); // الدولة التابعة لها
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
