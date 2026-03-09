<?php

namespace Modules\DocumentRepository\app\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'ref_document_type';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'description', 'category'];

    // Relasi balik ke Kategori
    public function documentCategory()
    {
        return $this->belongsTo(DocumentCategory::class, 'category', 'id');
    }
}