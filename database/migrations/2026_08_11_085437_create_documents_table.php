<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('document_categories')->onDelete('cascade');
            
            // Polymorphic relationship
            $table->string('related_type');
            $table->unsignedBigInteger('related_id');
            $table->index(['related_type', 'related_id']);
            
            $table->string('name');
            $table->string('file_path');
            $table->string('file_name');
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('file_type')->nullable(); // pdf, doc, jpg, etc.
            $table->text('description')->nullable();
            $table->date('upload_date');
            $table->date('expires_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};