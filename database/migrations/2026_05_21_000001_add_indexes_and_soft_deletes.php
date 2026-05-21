<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Index pada penilaians.periode — sering di-query untuk kalkulasi SAW & filter
        Schema::table('penilaians', function (Blueprint $table) {
            $table->index('periode', 'idx_penilaians_periode');
        });

        // Index pada hasils.periode — sering di-filter di HasilController & DashboardController
        Schema::table('hasils', function (Blueprint $table) {
            $table->index('periode', 'idx_hasils_periode');
            $table->index('status', 'idx_hasils_status');
        });

        // Index pada wargas.status_aktif — semua query warga selalu filter ini
        Schema::table('wargas', function (Blueprint $table) {
            $table->index('status_aktif', 'idx_wargas_status_aktif');
        });

        // Soft delete — tambah kolom deleted_at ke wargas
        Schema::table('wargas', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropIndex('idx_penilaians_periode');
        });

        Schema::table('hasils', function (Blueprint $table) {
            $table->dropIndex('idx_hasils_periode');
            $table->dropIndex('idx_hasils_status');
        });

        Schema::table('wargas', function (Blueprint $table) {
            $table->dropIndex('idx_wargas_status_aktif');
            $table->dropSoftDeletes();
        });
    }
};
