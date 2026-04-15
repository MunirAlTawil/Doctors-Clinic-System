<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSetting extends Model
{
    protected $fillable = [
        'admin_profit_percent',
    ];

    protected function casts(): array
    {
        return [
            'admin_profit_percent' => 'decimal:2',
        ];
    }
}
