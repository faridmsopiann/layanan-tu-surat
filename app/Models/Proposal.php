<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    // Tambahkan kolom-kolom yang ingin diisi secara mass-assignment
    protected $fillable = [
        'kode_pengajuan',
        'jenis_proposal',
        'nomor_agenda',
        'tanggal_surat',
        'nomor_surat',
        'asal_surat',
        'hal',
        'diterima_tanggal',
        'untuk',
        'status_disposisi',
        'dari',
        'tujuan_disposisi',
        'pesan_disposisi',
        'alasan_penolakan',
        'pemohon_id',
        'soft_file',
    ];

    // Di dalam model Proposal (app/Models/Proposal.php)
    public function pemohon()
    {
        return $this->belongsTo(User::class, 'pemohon_id');  // Menyatakan bahwa 'pemohon_id' mengarah ke model User
    }

    // Model Proposal.php
    public function modalDisposisi()
    {
        return $this->hasMany(ModalDisposisi::class, 'proposal_id');
    }
}
