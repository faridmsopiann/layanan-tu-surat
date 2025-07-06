<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeranTugas extends Model
{
    use HasFactory;

    protected $table = 'perans';
    protected $fillable = ['nama'];
}
