<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    protected $fillable = ['request_id','action','approver_id','note','created_at'];

    public function request() {
        return $this->belongsTo(Request::class);
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
