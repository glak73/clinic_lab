<!-- resources/views/dashboard.blade.php -->


@php
use Carbon\Carbon;
@endphp

<div class="container">
    <div class="pdf-export">
        <a href="{{ route('pdf.appointments') }}"
           class="btn btn-primary">
            Скачать PDF с записями
        </a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $title }}</h2>
                    <p>{{ $pageHeader }}</p>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="appointments-list">
                            @foreach($appointments as $appointment)
                                <div class="appointment-item">
                                    <div class="appointment-header">
                                        <h3>{{ $appointment->doctor->name }} ({{ $appointment->doctor->specialty }})</h3>
                                        <p class="appointment-date">
                                            {{ Carbon::parse($appointment->date)->format('d.m.Y') }}
                                            в {{ $appointment->time }}
                                        </p>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="badge {{
                                            ($appointment->status == 'pending' ? 'badge-warning' :
                                            ($appointment->status == 'visited' ? 'badge-success' : 'badge-danger'))
                                        }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <div class="appointment-actions">
                                        @if($appointment->status == 'pending' &&
                                            Carbon::parse($appointment->date)->isFuture())
                                            <form action="{{ route('appointments.cancel', $appointment->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Вы уверены, что хотите отменить запись?')">
                                                    Отменить запись
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pagination-links">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="no-appointments">
                            <p>У вас нет активных записей.</p>
                            <a href="{{ route('appointments.create') }}"
                               class="btn btn-primary">
                                Записаться на прием
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

