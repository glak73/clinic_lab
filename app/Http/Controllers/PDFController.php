<?php

// app/Http/Controllers/PDFController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

use App\Models\Appointment;
use Carbon\Carbon;
class PDFController extends Controller
{
    public function generateAppointmentsPDF()
    {
        $appointments = Appointment::where('patient_name', auth()->user()->email)
        ->orderBy('date', 'asc')
        ->orderBy('time', 'asc');

        $pdf = PDF::loadView('appointments.pdf', [
            'appointments' => $appointments,
            'user' => auth()->user()
        ]);


        return $pdf->download('my_appointments.pdf');
    }
}
