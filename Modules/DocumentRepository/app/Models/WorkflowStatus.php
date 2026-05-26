<?php

namespace Modules\DocumentRepository\app\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStatus extends Model
{
    protected $table = 'mst_workflow_status';
    const UPDATED_AT = null; // Karena di database cuma ada created_at

    protected $fillable = ['description', 'is_active', 'created_at'];
}