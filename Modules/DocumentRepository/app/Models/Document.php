<?php

namespace Modules\DocumentRepository\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\MasterData\app\Models\User;
use Modules\MasterData\app\Models\Unit;

class Document extends Model
{
    protected $table = 'trx_document';
    const UPDATED_AT = null; // Karena di database cuma ada created_at

    protected $fillable = [
        'document_id', 'document_title', 'document_type_id', 
        'unit_id', 'version', 'file_path', 'status', 
        'sifat_dokumen', 'is_ppid',
        'effective_date', 'expired_date', 'created_by', 'created_at'
    ];

    // Relasi ke Master Data
    public function type() {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id');
    }

    public function unit() {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function workflowStatus() {
        return $this->belongsTo(WorkflowStatus::class, 'status', 'id');
    }

    // Relasi ke Riwayat Versi (1 Dokumen punya Banyak Versi/Revisi)
    public function versions() {
        return $this->hasMany(DocumentVersion::class, 'document_id', 'id');
    }
}