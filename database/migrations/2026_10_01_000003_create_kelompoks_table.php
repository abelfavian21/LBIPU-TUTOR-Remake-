<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kelompok tutorial = 1 tutor + sekumpulan mahasiswa pada 1 semester
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->string('kode', 20);                    // contoh: L200N
            $table->string('kelas')->default('Reguler');
            $table->string('prodi')->default('Informatika');
            $table->string('wa_group_url')->nullable();
            $table->timestamps();

            $table->unique(['semester_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
    }
};
