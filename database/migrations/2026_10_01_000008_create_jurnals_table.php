<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->unique()->constrained('pertemuans')->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedSmallInteger('jumlah_hadir')->default(0);
            $table->string('materi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
