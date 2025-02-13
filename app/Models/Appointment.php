<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Appointment extends Model
{
    protected $guarded = [];
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
    public function getPatientNameAttribute($value)
{
    return ($this->user ? $this->user->name : ($value ?? 'Не указано'));
}
}

