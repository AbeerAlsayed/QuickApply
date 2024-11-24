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
        Schema::create('countries', function (Blueprint $table) {
        $table->id(); // رقم الدولة
        $table->string('name')->unique(); // اسم الدولة
        $table->string('code', 5)->unique(); // كود الدولة
        $table->timestamps(); // تاريخ الإنشاء والتحديث
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
