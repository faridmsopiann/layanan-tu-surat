<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PegawaiPenugasan extends Model
{
    use HasFactory;

    protected $table = 'pegawai_penugasans';
    protected $fillable = ['nama', 'nip', 'unit_id', 'jabatan_id'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}
