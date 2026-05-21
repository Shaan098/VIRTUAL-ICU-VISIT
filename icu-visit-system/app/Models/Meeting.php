<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_request_id', 'room_name', 'room_password', 'jitsi_url',
        'scheduled_at', 'started_at', 'ended_at', 'status',
        'host_id', 'duration_minutes', 'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at'   => 'datetime',
        'ended_at'     => 'datetime',
    ];

    public function visitRequest(): BelongsTo
    {
        return $this->belongsTo(VisitRequest::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function getJitsiRoomUrlAttribute(): string
    {
        $domain = 'meet.jit.si';
        return "https://{$domain}/{$this->room_name}";
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'primary',
            'active'    => 'success',
            'completed' => 'info',
            'cancelled' => 'secondary',
            default     => 'secondary',
        };
    }
}
