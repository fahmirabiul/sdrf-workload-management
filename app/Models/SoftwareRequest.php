<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'unit_id',
        'application_id',
        'request_type',
        'title',
        'description',
        'business_impact',
        'target_used_date',
        'attachment_path',
        'status',
        'meeting_notes',
        'rating',
        'rating_feedback'
    ];

    // relasi Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // relasi Application
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
