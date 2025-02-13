<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('patient_name', auth()->user()->email)
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->paginate(10);

        return view('dashboard', [
            'appointments' => $appointments,
            'title' => 'Мои записи',
            'pageHeader' => 'Список ваших записей к врачу'
        ]);
    }
}
