<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswas';

    protected $fillable = [
        'kelompok_id', 'nim', 'nama', 'no_telp', 'email',
        'prodi', 'angkatan', 'jenis_kelamin', 'status',
    ];

    private const WARNA_AVATAR = [
        'bg-blue-100 text-blue-700',
        'bg-purple-100 text-purple-700',
        'bg-amber-100 text-amber-800',
        'bg-rose-100 text-rose-700',
        'bg-emerald-100 text-emerald-800',
        'bg-indigo-100 text-indigo-700',
        'bg-cyan-100 text-cyan-800',
        'bg-teal-100 text-teal-800',
        'bg-pink-100 text-pink-700',
    ];

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Hitung rekap per mahasiswa: jumlah hadir/izin/absen, jumlah nilai terisi, rata-rata nilai.
     * Hasil: atribut hadir_count, izin_count, absen_count, nilai_count, rata_nilai.
     *
     * @param  iterable<int>  $pertemuanIds  batasi ke pertemuan milik kelompok ini
     */
    public function scopeWithRekap(Builder $query, iterable $pertemuanIds): Builder
    {
        $ids = collect($pertemuanIds)->all();

        return $query->withCount([
            'presensis as hadir_count' => fn ($q) => $q->whereIn('pertemuan_id', $ids)->where('status', 'hadir'),
            'presensis as izin_count' => fn ($q) => $q->whereIn('pertemuan_id', $ids)->where('status', 'izin'),
            'presensis as absen_count' => fn ($q) => $q->whereIn('pertemuan_id', $ids)->where('status', 'absen'),
            'nilais as nilai_count' => fn ($q) => $q->whereIn('pertemuan_id', $ids)->whereNotNull('nilai'),
        ])->withAvg(['nilais as rata_nilai' => fn ($q) => $q->whereIn('pertemuan_id', $ids)], 'nilai');
    }

    /** Dua kata pertama, contoh "MALIKA ABEL ..." -> "MA". */
    public function getInisialAttribute(): string
    {
        $kata = preg_split('/\s+/', trim($this->nama), -1, PREG_SPLIT_NO_EMPTY) ?: ['?'];
        $inisial = mb_substr($kata[0], 0, 1).(isset($kata[1]) ? mb_substr($kata[1], 0, 1) : '');

        return mb_strtoupper($inisial);
    }

    public function getAvatarClassAttribute(): string
    {
        return self::WARNA_AVATAR[$this->id % count(self::WARNA_AVATAR)];
    }

    /** Nomor telepon -> tautan WhatsApp (08xx -> 628xx). */
    public function getWaUrlAttribute(): ?string
    {
        $digit = preg_replace('/\D/', '', (string) $this->no_telp);
        if ($digit === '') {
            return null;
        }
        if (str_starts_with($digit, '0')) {
            $digit = '62'.substr($digit, 1);
        }

        return 'https://wa.me/'.$digit;
    }
}
