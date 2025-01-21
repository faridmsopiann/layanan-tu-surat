<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'user_id',
        'jenis',
        'status',
        'tanggal_proses',
        'tanggal_selesai',
        'catatan',
        'updated_at',
    ];

    function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function documents()
    {
        return $this->hasMany(SpjDocument::class);
    }

    function rating()
    {
        return $this->hasOne(SpjRating::class);
    }
}
