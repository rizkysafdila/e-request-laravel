<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    /** @use HasFactory<\Database\Factories\RequestFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'request_type',
        'status',
        'attachment_path',
        'created_by'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs() {
        return $this->hasMany(ApprovalLog::class, 'request_id');
    }

    public function canEdit(): bool {
        return $this->status === 'draft';
    }
}
