<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ahp_comparisons');
        Schema::dropIfExists('hasils');
        Schema::dropIfExists('penilaians');
        Schema::dropIfExists('wargas');
        Schema::dropIfExists('kriterias');

        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->enum('tipe', ['benefit', 'cost']);
            $table->decimal('bobot', 8, 4)->nullable();
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('ahp_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_baris_id')->constrained('kriterias')->cascadeOnDelete();
            $table->foreignId('kriteria_kolom_id')->constrained('kriterias')->cascadeOnDelete();
            $table->decimal('nilai', 6, 4);
            $table->timestamps();
        });

        Schema::create('wargas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->text('alamat')->nullable();
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->string('kelurahan', 100);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas')->cascadeOnDelete();
            $table->foreignId('kriteria_id')->constrained('kriterias')->cascadeOnDelete();
            $table->string('nilai_raw', 100);
            $table->tinyInteger('nilai_numerik');
            $table->string('periode', 7); // YYYY-MM
            $table->timestamps();
            $table->unique(['warga_id', 'kriteria_id', 'periode']);
        });

        Schema::create('hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas')->cascadeOnDelete();
            $table->string('periode', 7);
            $table->decimal('skor_akhir', 8, 6);
            $table->unsignedInteger('ranking');
            $table->enum('status', ['layak', 'tidak_layak', 'prioritas']);
            $table->timestamps();
            $table->unique(['warga_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasils');
        Schema::dropIfExists('penilaians');
        Schema::dropIfExists('wargas');
        Schema::dropIfExists('ahp_comparisons');
        Schema::dropIfExists('kriterias');
    }
};
