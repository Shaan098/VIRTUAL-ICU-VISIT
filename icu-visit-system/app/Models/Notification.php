<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'message', 'type',
        'read_at', 'related_model', 'related_id', 'action_url',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'success' => 'fa-check-circle text-success',
            'warning' => 'fa-exclamation-triangle text-warning',
            'danger'  => 'fa-times-circle text-danger',
            default   => 'fa-info-circle text-info',
        };
    }

    public function getBgColorAttribute(): string
    {
        return match($this->type) {
            'success' => '#d4edda',
            'warning' => '#fff3cd',
            'danger'  => '#f8d7da',
            default   => '#d1ecf1',
        };
    }
}
