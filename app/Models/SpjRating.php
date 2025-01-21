<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpjRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'spj_id',
        'user_id',
        'rating',
        'catatan',
    ];
}
