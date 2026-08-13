<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            // Business signup fields
            $table->string('password')->nullable()->after('email');
            $table->string('contact_person')->nullable()->after('phone');
            $table->string('business_type')->nullable()->after('address');
            $table->string('registration_number')->nullable()->after('business_type');
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->boolean('is_approved')->default(false)->after('is_active');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            $table->rememberToken()->after('approved_at');
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'contact_person',
                'business_type',
                'registration_number',
                'email_verified_at',
                'is_approved',
                'approved_at',
                'remember_token'
            ]);
        });
    }
};