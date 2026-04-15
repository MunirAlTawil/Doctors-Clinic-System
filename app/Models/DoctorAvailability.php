<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    protected $fillable = [
        'doctor_id',
        'work_date',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'is_available' => 'boolean',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
