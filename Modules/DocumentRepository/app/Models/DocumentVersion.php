<?php

namespace Modules\DocumentRepository\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\MasterData\app\Models\User;

class DocumentVersion extends Model
{
    protected $table = 'trx_document_version';
    public $timestamps = false; // Tidak ada created_at/updated_at default Laravel

    protected $fillable = [
        'document_id', 'version', 'file_path', 
        'change_note', 'approved_by', 'approved_date'
    ];

    public function document() {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}