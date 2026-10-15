<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    protected $table = 'kelompoks';

    protected $fillable = ['tutor_id', 'semester_id', 'kode', 'kelas', 'prodi', 'wa_group_url'];

    protected static function booted(): void
    {
        // Setiap kelompok baru otomatis punya N pertemuan (default 14)
        static::created(function (Kelompok $kelompok) {
            $topik = config('lpidb.topik_default', []);
            foreach (range(1, (int) config('lpidb.jumlah_pertemuan', 14)) as $nomor) {
                $kelompok->pertemuans()->create([
                    'nomor' => $nomor,
                    'topik' => $topik[$nomor - 1] ?? null,
                ]);
            }
        });
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function pertemuans(): HasMany
    {
        return $this->hasMany(Pertemuan::class)->orderBy('nomor');
    }
}
