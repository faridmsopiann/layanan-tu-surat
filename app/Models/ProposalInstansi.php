<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalInstansi extends Model
{
    use HasFactory;

    protected $table = 'proposal_instansis';

    protected $fillable = [
        'proposal_id',
        'instansi_id',
        'nama_manual',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }
}
