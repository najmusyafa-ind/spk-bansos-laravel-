<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->enum('sifat', ['Benefit', 'Cost']);
            $table->float('bobot')->default(0); // Bobot hasil AHP
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
