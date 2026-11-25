<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('moderation_status', 30)->nullable()->after('status');
        });

        // Sinkronkan data lama yang sudah ada di database
        DB::table('products')
            ->whereIn('status', ['tersedia', 'approved'])
            ->update(['moderation_status' => 'approved']);

        DB::table('products')
            ->where('status', 'rejected')
            ->update(['moderation_status' => 'rejected']);

        // Untuk barang lama yang sudah terlanjur berstatus 'dihapus'
        DB::table('products')
            ->where('status', 'dihapus')
            ->whereNotNull('rejection_reason')
            ->where('rejection_reason', '!=', '')
            ->update(['moderation_status' => 'rejected']);

        DB::table('products')
            ->where('status', 'dihapus')
            ->where(function ($q) {
                $q->whereNull('rejection_reason')
                  ->orWhere('rejection_reason', '');
            })
            ->update(['moderation_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('moderation_status');
        });
    }
};