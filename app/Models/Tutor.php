<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tutor extends Authenticatable
{
    protected $table = 'tutors';

    protected $fillable = ['username', 'password', 'nim', 'nama', 'no_telp', 'email', 'status'];

    protected $hidden = ['password', 'remember_token'];

    private ?Kelompok $kelompokAktifCache = null;

    private bool $kelompokAktifResolved = false;

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function kelompoks(): HasMany
    {
        return $this->hasMany(Kelompok::class);
    }

    public function jurnals(): HasMany
    {
        return $this->hasMany(Jurnal::class);
    }

    /** Kelompok tutorial milik tutor ini pada semester yang sedang aktif. */
    public function kelompokAktif(): ?Kelompok
    {
        if (! $this->kelompokAktifResolved) {
            $this->kelompokAktifCache = $this->kelompoks()
                ->whereHas('semester', fn ($q) => $q->where('is_active', true))
                ->with('semester')
                ->latest('id')
                ->first();
            $this->kelompokAktifResolved = true;
        }

        return $this->kelompokAktifCache;
    }

    /** Inisial = huruf pertama kata pertama + huruf pertama kata terakhir. */
    public function getInisialAttribute(): string
    {
        $kata = preg_split('/\s+/', trim($this->nama), -1, PREG_SPLIT_NO_EMPTY) ?: ['?'];
        $awal = mb_substr($kata[0], 0, 1);
        $akhir = count($kata) > 1 ? mb_substr(end($kata), 0, 1) : '';

        return mb_strtoupper($awal.$akhir);
    }

    /** Nama pendek untuk sidebar, contoh: "M. ABEL FAVIAN F." */
    public function getNamaSingkatAttribute(): string
    {
        $kata = preg_split('/\s+/', trim($this->nama), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (count($kata) <= 3) {
            return mb_strtoupper($this->nama);
        }
        $kata[0] = mb_substr($kata[0], 0, 1).'.';
        $kata = array_slice($kata, 0, 3);
        $kata[2] = mb_substr($kata[2], 0, 1).'.';

        return mb_strtoupper(implode(' ', $kata));
    }
}
