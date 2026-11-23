<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('harga'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('moderated_at')->nullable()->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason', 'moderated_at']);
        });
    }
};