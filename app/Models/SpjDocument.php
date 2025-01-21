<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpjDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'spj_id',
        'spj_document_category_id',
        'file_url',
        'updated_at',
    ];

    function spj()
    {
        return $this->belongsTo(Spj::class);
    }

    function category()
    {
        return $this->belongsTo(SpjDocumentCategory::class, 'spj_document_category_id', 'id');
    }
}
