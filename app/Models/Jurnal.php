<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jurnal extends Model
{
    protected $table = 'jurnals';

    protected $fillable = ['pertemuan_id', 'tutor_id', 'tanggal', 'jumlah_hadir', 'materi', 'catatan'];

    protected function casts(): array
    {
        return ['tanggal' => 'date', 'jumlah_hadir' => 'integer'];
    }

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }
}
