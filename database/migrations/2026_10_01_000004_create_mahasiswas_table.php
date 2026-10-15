<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->nullable()->constrained('kelompoks')->nullOnDelete();
            $table->string('nim', 20)->unique();
            $table->string('nama');
            $table->string('no_telp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('prodi')->default('Informatika');
            $table->string('angkatan', 4)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('status', ['aktif', 'cuti', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
