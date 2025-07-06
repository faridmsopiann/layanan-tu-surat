<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KopSurat extends Model
{
    protected $guarded = []; 

    public function getKopSuratUrlAttribute()
    {
        return $this->kop_surat ? asset($this->kop_surat) : null;
    }

    public function getFooterUrlAttribute()
    {
        return $this->footer ? asset($this->footer) : null;
    }
}
