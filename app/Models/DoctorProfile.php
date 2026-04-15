<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'education_stage',
        'profile_image',
        'bio',
        'hourly_rate',
        'work_start_time',
        'work_end_time',
        'off_day_1',
        'off_day_2',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'date',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
