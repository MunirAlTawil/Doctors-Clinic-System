<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'is_active',
        'gender',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function doctorProfile(): HasOne
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function doctorSpecialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty', 'doctor_id', 'specialty_id');
    }

    public function doctorAvailabilities(): HasMany
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id');
    }

    public function patientAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function doctorAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function scopeActiveAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin')->where('is_active', true);
    }
}
