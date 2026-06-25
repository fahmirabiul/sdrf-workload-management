<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHistoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_request_id',
        'development_project_id',
        'user_id',
        'action_type',
        'old_value',
        'new_value',
        'reason',
    ];
}
