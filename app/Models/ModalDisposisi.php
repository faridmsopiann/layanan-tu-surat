<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalDisposisi extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default (modal_disposisi)
    protected $table = 'modal_disposisi';

    // Define the fillable fields
    protected $fillable = [
        'proposal_id',
        'tujuan',
        'status',
        'tanggal_diterima',
        'tanggal_proses',
        'diverifikasi_oleh',
        'keterangan',
    ];

    // Define the dates as Carbon instances
    protected $dates = [
        'tanggal_diterima',
        'tanggal_proses',
    ];

    // Model ModalDisposisi.php
    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
}
