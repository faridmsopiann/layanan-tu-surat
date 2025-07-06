<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalPenugasan extends Model
{
    use HasFactory;

    protected $table = 'proposal_penugasans';

    protected $fillable = [
        'proposal_id',
        'pegawai_id',
        'nama_manual',
        'peran_tugas_id',
        'unit_asal',
        'jabatan',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function pegawaiPenugasan()
    {
        return $this->belongsTo(PegawaiPenugasan::class, 'pegawai_id');
    }

    public function peranTugas()
    {
        return $this->belongsTo(PeranTugas::class);
    }
}
