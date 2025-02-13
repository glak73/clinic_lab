<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Массив данных для создания врачей
        $doctorsData = [
            [
                'name' => 'Иванова Мария Петровна',
                'specialty' => 'Терапевт',
                'working_hours' => $this->getDefaultWorkingHours()
            ],
            [
                'name' => 'Петров Сергей Николаевич',
                'specialty' => 'Хирург',
                'working_hours' => $this->getDefaultWorkingHours()
            ],
            [
                'name' => 'Сидорова Анна Михайловна',
                'specialty' => 'Кардиолог',
                'working_hours' => $this->getDefaultWorkingHours()
            ],
            [
                'name' => 'Козлов Дмитрий Алексеевич',
                'specialty' => 'Невролог',
                'working_hours' => $this->getDefaultWorkingHours()
            ],
            [
                'name' => 'Николаева Елена Васильевна',
                'specialty' => 'Эндокринолог',
                'working_hours' => $this->getDefaultWorkingHours()
            ]
        ];

        // Создаем записи в базе данных
        foreach ($doctorsData as $doctorData) {
            Doctor::updateOrCreate(
                ['name' => $doctorData['name']],
                $doctorData
            );
        }
    }

    /**
     * Возвращает стандартный график работы врача
     *
     * @return array
     */
    private function getDefaultWorkingHours(): array
    {
        return [
            'Monday' => ['start' => '09:00', 'end' => '18:00'],
            'Tuesday' => ['start' => '09:00', 'end' => '18:00'],
            'Wednesday' => ['start' => '09:00', 'end' => '18:00'],
            'Thursday' => ['start' => '09:00', 'end' => '18:00'],
            'Friday' => ['start' => '09:00', 'end' => '18:00'],
            'Saturday' => ['start' => '10:00', 'end' => '15:00'],
            'Sunday' => ['start' => null, 'end' => null] // выходной день
        ];
    }
}
