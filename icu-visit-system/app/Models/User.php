<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'specialty',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Role helpers ──────────────────────────────────────────
    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isDoctor(): bool { return $this->role === 'doctor'; }
    public function isFamily(): bool { return $this->role === 'family'; }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'admin'  => 'danger',
            'doctor' => 'primary',
            'family' => 'success',
            default  => 'secondary',
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initials = urlencode(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initials}&background=1a73e8&color=fff&size=128";
    }

    // ── Relationships ─────────────────────────────────────────
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'assigned_doctor_id');
    }

    public function visitRequests(): HasMany
    {
        return $this->hasMany(VisitRequest::class, 'requested_by');
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->appNotifications()->whereNull('read_at');
    }

    public function hostedMeetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'host_id');
    }
}
