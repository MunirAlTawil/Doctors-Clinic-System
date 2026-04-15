<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'slot_date',
        'slot_start',
        'slot_end',
    ];

    protected function casts(): array
    {
        return [
            'slot_date' => 'date',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
