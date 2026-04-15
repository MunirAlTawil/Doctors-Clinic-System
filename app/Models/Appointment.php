<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'specialty_id',
        'appointment_date',
        'start_time',
        'hours_count',
        'end_time',
        'hourly_rate',
        'total_amount',
        'admin_profit_percent',
        'admin_profit_amount',
        'doctor_net_amount',
        'payment_method',
        'payment_status',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'hourly_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'admin_profit_percent' => 'decimal:2',
            'admin_profit_amount' => 'decimal:2',
            'doctor_net_amount' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(AppointmentSlot::class);
    }

    public function cardPayment(): HasOne
    {
        return $this->hasOne(CardPayment::class);
    }
}
