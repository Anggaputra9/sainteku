<?php

namespace Modules\DocumentRepository\app\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $table = 'ref_document_category';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'description'];

    // Relasi ke Tipe Dokumen (1 Kategori punya Banyak Tipe)
    public function types()
    {
        return $this->hasMany(DocumentType::class, 'category', 'id');
    }
}