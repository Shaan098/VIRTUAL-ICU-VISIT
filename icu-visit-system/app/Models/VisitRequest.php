<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VisitRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'requested_by', 'assigned_doctor_id',
        'requested_date', 'requested_time', 'reason',
        'status', 'rejection_reason', 'admin_notes',
    ];

    protected $casts = [
        'requested_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function assignedDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_doctor_id');
    }

    public function meeting(): HasOne
    {
        return $this->hasOne(Meeting::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'warning',
            'approved'  => 'success',
            'rejected'  => 'danger',
            'completed' => 'info',
            'cancelled' => 'secondary',
            default     => 'secondary',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'fa-clock',
            'approved'  => 'fa-check-circle',
            'rejected'  => 'fa-times-circle',
            'completed' => 'fa-flag-checkered',
            'cancelled' => 'fa-ban',
            default     => 'fa-circle',
        };
    }
}
