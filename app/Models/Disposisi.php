<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisi';

    protected $fillable = [
        'pengajuan_surat_id',
        'jabatan',
        'status',
        'approval',
        'tanggal_terima',
        'tanggal_proses',
    ];

    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_surat_id');
    }
}
