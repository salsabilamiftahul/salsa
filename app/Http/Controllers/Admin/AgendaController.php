<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        // Filter pencarian dan tanggal.
        $search = trim((string) $request->query('q', ''));
        $filterDay = trim((string) $request->query('day', ''));
        $filterDate = trim((string) $request->query('date', ''));
        $filterYear = trim((string) $request->query('year', ''));

        $query = Agenda::query()->latest();
        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }
        if ($filterDate !== '') {
            $query->whereDate('starts_at', $filterDate);
        }
        if ($filterYear !== '' && is_numeric($filterYear)) {
            $query->whereYear('starts_at', (int) $filterYear);
        }
        if ($filterDay !== '' && is_numeric($filterDay)) {
            $dayIndex = (int) $filterDay - 1; // 0=Mon ... 6=Sun
            if ($dayIndex >= 0 && $dayIndex <= 6) {
                // Sesuaikan SQL untuk tiap driver.
                $driver = DB::connection()->getDriverName();
                if ($driver === 'mysql') {
                    $query->whereRaw('WEEKDAY(starts_at) = ?', [$dayIndex]);
                } elseif ($driver === 'pgsql') {
                    $pgsqlIndex = ($dayIndex + 1) % 7; // 0=Sun ... 6=Sat
                    $query->whereRaw('EXTRACT(DOW FROM starts_at) = ?', [$pgsqlIndex]);
                } elseif ($driver === 'sqlite') {
                    $sqliteIndex = ($dayIndex + 1) % 7; // 0=Sun ... 6=Sat
                    $query->whereRaw("strftime('%w', starts_at) = ?", [$sqliteIndex]);
                }
            }
        }

        return view('admin.agendas.index', [
            'agendas' => $query->paginate(10)->appends([
                'q' => $search,
                'day' => $filterDay,
                'date' => $filterDate,
                'year' => $filterYear,
            ]),
            'search' => $search,
            'filterDay' => $filterDay,
            'filterDate' => $filterDate,
            'filterYear' => $filterYear,
        ]);
    }

    public function create(): View
    {
        return view('admin.agendas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi + aturan jam selesai setelah jam mulai.
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'agenda_date' => ['required', 'date'],
            'agenda_time' => ['required', 'date_format:H:i'],
            'agenda_time_end' => ['nullable', 'date_format:H:i'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validator->after(function ($validator) use ($request) {
            $start = $request->input('agenda_time');
            $end = $request->input('agenda_time_end');
            if ($start && $end) {
                $startTime = Carbon::createFromFormat('H:i', $start);
                $endTime = Carbon::createFromFormat('H:i', $end);
                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $validator->errors()->add('agenda_time_end', 'Jam selesai harus lebih besar dari jam mulai.');
                }
            }
        });
        $data = $validator->validate();

        // Gabungkan tanggal dan jam ke datetime.
        $startsAt = $data['agenda_date'] . ' ' . $data['agenda_time'] . ':00';
        $endsAt = !empty($data['agenda_time_end'])
            ? $data['agenda_date'] . ' ' . $data['agenda_time_end'] . ':00'
            : null;

        Agenda::query()->create([
            'title' => $data['title'],
            'description' => null,
            'image_path' => null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.agendas.index')
            ->with('status', 'Agenda berhasil dibuat.');
    }

    public function edit(Agenda $agenda): View
    {
        return view('admin.agendas.edit', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda): RedirectResponse
    {
        // Validasi + aturan jam selesai setelah jam mulai.
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'agenda_date' => ['required', 'date'],
            'agenda_time' => ['required', 'date_format:H:i'],
            'agenda_time_end' => ['nullable', 'date_format:H:i'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validator->after(function ($validator) use ($request) {
            $start = $request->input('agenda_time');
            $end = $request->input('agenda_time_end');
            if ($start && $end) {
                $startTime = Carbon::createFromFormat('H:i', $start);
                $endTime = Carbon::createFromFormat('H:i', $end);
                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $validator->errors()->add('agenda_time_end', 'Jam selesai harus lebih besar dari jam mulai.');
                }
            }
        });
        $data = $validator->validate();

        // Gabungkan tanggal dan jam ke datetime.
        $startsAt = $data['agenda_date'] . ' ' . $data['agenda_time'] . ':00';
        $endsAt = !empty($data['agenda_time_end'])
            ? $data['agenda_date'] . ' ' . $data['agenda_time_end'] . ':00'
            : null;

        $agenda->fill([
            'title' => $data['title'],
            'description' => null,
            'image_path' => $agenda->image_path,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
        $agenda->save();

        return redirect()->route('admin.agendas.index')
            ->with('status', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda): RedirectResponse
    {
        $agenda->delete();

        return redirect()->route('admin.agendas.index')
            ->with('status', 'Agenda berhasil dihapus.');
    }
}
