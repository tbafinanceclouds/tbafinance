<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('regular');
            $table->decimal('interest_rate', 5, 2);
            $table->integer('max_term_months')->default(12);
            $table->decimal('max_amount', 15, 2);
            $table->decimal('processing_fee', 15, 2)->default(0);
            $table->boolean('requires_guarantor')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};