<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PejabatPenandatangan extends Model
{
    protected $guarded = [];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}