<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CardPayment extends Model
{
    protected $fillable = [
        'appointment_id',
        'card_type',
        'cardholder_name',
        'card_number_last4',
        'expiry_month',
        'expiry_year',
        'status',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
