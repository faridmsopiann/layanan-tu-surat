<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalPenugasan extends Model
{
    use HasFactory;

    protected $table = 'proposal_penugasan';

    protected $fillable = [
        'proposal_id',
        'dosen_id',
        'nama_manual',
        'peran_tugas_id',
        'unit_asal',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function peranTugas()
    {
        return $this->belongsTo(PeranTugas::class);
    }
}
