<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            // Yearly pricing
            $table->decimal('yearly_price', 15, 2)->nullable()->after('price');
            
            // Trial period
            $table->integer('trial_days')->default(0)->after('yearly_price');
            
            // Discount/Promo
            $table->string('promo_code')->nullable()->after('trial_days');
            $table->decimal('promo_discount', 5, 2)->nullable()->after('promo_code'); // percentage
            $table->timestamp('promo_expires_at')->nullable()->after('promo_discount');
            
            // Popular badge
            $table->boolean('is_popular')->default(false)->after('is_active');
            
            // Max features limit (0 = unlimited)
            $table->integer('max_features')->default(0)->after('max_users');
        });
    }

    public function down()
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn([
                'yearly_price',
                'trial_days',
                'promo_code',
                'promo_discount',
                'promo_expires_at',
                'is_popular',
                'max_features'
            ]);
        });
    }
};