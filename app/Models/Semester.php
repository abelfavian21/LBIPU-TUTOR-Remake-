<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $table = 'semesters';

    protected $fillable = ['kode', 'nama', 'tanggal_mulai', 'tanggal_selesai', 'is_active'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function kelompoks(): HasMany
    {
        return $this->hasMany(Kelompok::class);
    }

    public static function aktif(): ?self
    {
        return static::where('is_active', true)->latest('id')->first();
    }
}
