<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade'); // The guarantor (must be a member)
            $table->string('relationship'); // e.g., Spouse, Parent, Colleague
            $table->decimal('amount_guaranteed', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->date('guarantee_date');
            $table->date('approval_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();

            // Ensure one member can't be guarantor for same loan twice
            $table->unique(['loan_id', 'member_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guarantors');
    }
};