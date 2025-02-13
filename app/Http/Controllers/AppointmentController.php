<?php

// app/Http/Controllers/AppointmentController.php
namespace App\Http\Controllers;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Doctor;

class AppointmentController extends Controller
{
    public function create(Request $request)
{
    // Получаем текущую дату и список дат за 30 дней
    $currentDate = Carbon::today();
    $dates = [];

    for ($i = 0; $i < 30; $i++) {
        $date = clone $currentDate;
        $date->addDays($i);
        $dates[$date->format('Y-m-d')] = [
            'date' => $date,
            'is_bookable' => true,
            'appointments' => []
        ];
    }

    // Загружаем занятые даты
    $busyDates = Appointment::whereIn('date', array_keys($dates))
        ->where('status', 'pending')
        ->get()
        ->groupBy('date');

    // Маркируем занятые даты и собираем информацию о записях
    foreach ($dates as &$dateInfo) {
        $dateString = $dateInfo['date']->format('Y-m-d');

        if ($busyDates->has($dateString)) {
            $dateInfo['appointments'] = $busyDates->get($dateString)
                ->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'time' => $appointment->time,
                        'patient_name' => $appointment->patient_name
                    ];
                })
                ->toArray();

            // Если все временные слоты заняты, маркируем дату как неактивную
            if (count($dateInfo['appointments']) >= config('appointments.slots_per_day')) {
                $dateInfo['is_bookable'] = false;
            }
        }
    }

    return view('appointments.calendar', [
        'doctors' => Doctor::all(),
        'dates' => $dates,
        'currentDate' => $currentDate->format('Y-m-d'),
        'workingHours' => config('appointments.working_hours')
    ]);
}

public function store(Request $request)
{
    // Получаем данные из формы
    $date = $request->input('date');
    $doctorId = $request->input('doctor_id');
    $time = $request->input('time');
    $patientName = $request->input('patient_name') ?? 'Не указано';

    // Валидация данных


    // Проверяем доступность выбранного времени
    $existingAppointment = Appointment::where([
        ['doctor_id', $doctorId],
        ['date', $date],
        ['time', $time]
    ])->first();

    if ($existingAppointment) {
        return redirect()->back()
            ->withErrors(['time' => 'Это время уже занято'])
            ->withInput();
    }

    // Создаём новую запись
    $appointment = Appointment::create([
        'doctor_id' => $doctorId,
        'date' => $date,
        'time' => $time,
        'patient_name' => $patientName,
        'status' => 'pending'
    ]);

    return redirect()->route('appointments.create');
}
}
