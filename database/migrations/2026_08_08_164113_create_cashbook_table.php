<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->date('transaction_date');
            $table->string('type'); // cash_in, cash_out
            $table->string('category'); // deposit, loan_repayment, withdrawal, expense, loan_disbursement, income
            $table->string('reference')->nullable();
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('payment_method')->default('cash'); // cash, bank, mobile_money, cheque
            $table->string('status')->default('completed');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashbook');
    }
};