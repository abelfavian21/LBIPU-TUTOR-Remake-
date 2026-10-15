<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->cascadeOnDelete();
            $table->unsignedTinyInteger('nomor');          // pertemuan ke-1 s/d ke-14
            $table->string('topik')->nullable();
            $table->date('tanggal')->nullable();           // tanggal pelaksanaan (harian)
            $table->timestamps();

            $table->unique(['kelompok_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuans');
    }
};
