<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $casts = [
        'working_hours' => 'array'
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    // Метод для проверки доступности времени
    public function isTimeAvailable(string $date, string $time): bool
    {
        return !Appointment::where([
            ['doctor_id', $this->id],
            ['date', $date],
            ['time', $time]
        ])->exists();
    }
}
