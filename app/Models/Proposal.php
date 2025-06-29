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
        'soft_file_link',
        'perlu_sk',
        'pihak_pembuat_sk',
        'perlu_ttd',
        'pihak_ttd',
        'jenis_kegiatan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi_kegiatan',
        'instansi_terkait',
    ];

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function modalDisposisi()
    {
        return $this->hasMany(ModalDisposisi::class, 'proposal_id');
    }

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class);
    }

    public function peran()
    {
        return $this->belongsTo(PeranTugas::class);
    }

    public function instansi()
    {
        return $this->hasMany(ProposalInstansi::class);
    }

    public function penugasan()
    {
        return $this->hasMany(ProposalPenugasan::class);
    }
}
