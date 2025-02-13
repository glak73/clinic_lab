<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Carbon\Carbon;
use App\Models\Comment;
class DoctorController extends Controller
{
    /**
     * Отображение списка всех врачей
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctors = Doctor::with('workingHours')->get();

        return view('doctors.index', [
            'doctors' => $doctors,
            'title' => 'Список врачей',
            'pageHeader' => 'Выберите врача для записи'
        ]);
    }

    /**
     * Показать профиль врача
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $doctor = Doctor::findOrFail($id);
        $comments = $doctor->comments()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('doctors.show', [
            'doctor' => $doctor,
            'comments' => $comments,
            'title' => "Профиль врача: {$doctor->name}",
            'pageHeader' => "Информация о враче"
        ]);
    }

    /**
     * Получить свободное время врача на определенную дату
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function availableTimes(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        // Получаем дату из запроса или используем текущую
        $date = $request->query('date')
            ? Carbon::parse($request->query('date'))
            : Carbon::today();

        // Получаем все занятые время за выбранную дату
        $busyTimes = Doctor::find($id)
            ->appointments()
            ->whereDate('date', $date->format('Y-m-d'))
            ->pluck('time')
            ->toArray();

        // Формируем список доступного времени
        $availableTimes = [];
        $workingHours = $doctor->working_hours[$date->dayOfWeek] ?? [];

        foreach ($workingHours as $hour => $isWorking) {
            if ($isWorking) {
                $time = Carbon::createFromTime($hour, 0)->format('H:i');
                if (!in_array($time, $busyTimes)) {
                    $availableTimes[] = [
                        'time' => $time,
                        'is_available' => true
                    ];
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $availableTimes,
            'message' => 'Свободное время успешно загружено'
        ]);
    }

    /**
     * Получить список всех специализаций
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function getSpecialties()
    {
        $specialties = Doctor::select('specialty')
            ->distinct()
            ->orderBy('specialty')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $specialties,
            'message' => 'Специализации успешно загружены'
        ]);
    }

    /**
     * Обновить информацию о враче
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->middleware('auth');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'working_hours' => 'required|array'
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($validated);

        return redirect()->route('doctors.show', $id)
            ->with('success', 'Информация о враче обновлена');
    }

    /**
     * Удалить врача
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->middleware('auth');

        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Врач успешно удален');
    }
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|min:5'
        ]);

        $comment = Comment::create([
            'doctor_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->input('content')
        ]);

        return redirect()->back()
            ->with('success', 'Комментарий успешно добавлен');
    }
}
