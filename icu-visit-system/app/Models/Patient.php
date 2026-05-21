<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'dob', 'gender', 'diagnosis', 'bed_number',
        'ward', 'status', 'assigned_doctor_id', 'admission_date',
        'notes', 'emergency_contact_name', 'emergency_contact_phone',
        'blood_group', 'age',
    ];

    protected $casts = [
        'dob'            => 'date',
        'admission_date' => 'date',
    ];

    public function assignedDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_doctor_id');
    }

    public function visitRequests(): HasMany
    {
        return $this->hasMany(VisitRequest::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'critical'   => 'danger',
            'active'     => 'primary',
            'stable'     => 'success',
            'discharged' => 'secondary',
            default      => 'secondary',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'critical'   => 'fa-heart-pulse text-danger',
            'active'     => 'fa-hospital text-primary',
            'stable'     => 'fa-check-circle text-success',
            'discharged' => 'fa-door-open text-secondary',
            default      => 'fa-circle text-secondary',
        };
    }
}
