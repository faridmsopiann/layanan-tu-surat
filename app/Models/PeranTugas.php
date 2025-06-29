<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeranTugas extends Model
{
    use HasFactory;

    protected $table = 'peran_tugas';
    protected $fillable = ['nama'];
}
