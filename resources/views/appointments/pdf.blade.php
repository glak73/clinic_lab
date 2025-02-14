@php
use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои записи к врачу</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .appointment-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .appointment-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }
        .status-pending { background-color: #ffeb3b; }
        .status-visited { background-color: #4caf50; }
        .status-cancelled { background-color: #f44336; }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Visit History</h1>
        <p>patient: {{ $user->name }}</p>
        <p>date: {{ Carbon::now()->format('d.m.Y') }}</p>
    </div>

    @if($appointments->count() > 0)
        @foreach($appointments->get() as $appointment)
            <div class="appointment-item">
                <div class="appointment-info">
                    <div>
                        <strong>{{ $appointment->doctor_name }} ({{ $appointment->specialty }})</strong><br>
                        {{ Carbon::parse($appointment->date)->format('d.m.Y') }}
                        в {{ $appointment->time }}
                    </div>
                    <span class="status-badge status-{{ $appointment->status }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
                <div class="appointment-details">
                    Статус: {{ ucfirst($appointment->status) }}
                    @if($appointment->status == 'visited')
                        <br>visit confirmed
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p>У вас нет записей к врачу.</p>
    @endif
</body>
</html>
