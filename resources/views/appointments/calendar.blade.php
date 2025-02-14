<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="calendar-container">
                @foreach($dates as $dateString => $dateInfo)
                <div class="calendar-day {{ !$dateInfo['is_bookable'] ? 'unavailable' : '' }}">
                    <div class="day-header">
                        <span class="day-number">{{ $dateInfo['date']->format('d') }}</span>
                        <span class="day-name">{{ $dateInfo['date']->format('D') }}</span>
                    </div>

                    @if($dateInfo['is_bookable'])
                        @foreach($doctors as $doctor)
                        <div class="doctor-slots">
                            <h3 class="doctor-name">
                                {{ $doctor->name }} ({{ $doctor->specialty }})
                            </h3>
                            <form action="{{ route('appointments.store') }}"
                                  method="POST"
                                  class="booking-form"
                                  data-date="{{ $dateString }}"
                                  data-doctor-id="{{ $doctor->id }}">
                                @csrf
                                <input type="hidden" name="date" value="{{ $dateString }}">
                                <input type="hidden" name="patient_name" value="{{ Auth()->user()->email  ?? 'не указано'}}">
                                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                                @php
                                    $busyTimes = array_column(
                                        $dateInfo['appointments'] ?? [],
                                        'time'
                                    );
                                @endphp

                                @for($hour = config('appointments.working_hours.start');
                                    $hour <= config('appointments.working_hours.end');
                                    $hour = date('H:i', strtotime($hour . '+' .
                                        config('appointments.working_hours.interval') . ' minutes')))
                                    <button type="submit"
                                            class="time-slot btn btn-sm btn-outline-primary"
                                            name="time"
                                            value="{{ $hour }}"
                                            @if(in_array($hour, $busyTimes))
                                                disabled
                                            @endif>
                                        {{ $hour }}
                                    </button>
                                @endfor
                            </form>
                        </div>
                        @endforeach
                    @else
                        <div class="booked-message">
                            Все слоты на эту дату забронированы
                        </div>
                    @endif

                    @if(isset($dateInfo['appointments']) && count($dateInfo['appointments']) > 0)
                        <div class="booked-times">
                            <strong>Забронированные слоты:</strong>
                            @foreach($dateInfo['appointments'] as $appointment)
                                <div class="booked-time">
                                    {{ $appointment['time'] }} -
                                    {{ $appointment['patient_name'] }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
