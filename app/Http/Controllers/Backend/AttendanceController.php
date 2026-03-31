<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\EmployeeAttendanceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(EmployeeAttendanceDataTable $dataTable)
    {
        $user = Auth::guard('sanctum')->user();

        // $employee = Employee::find($user->id);
        $employee = $user->employee;

        // dd($user, $employee);

        return $dataTable->with([
            'employee_name' => $user->name ?? '-',
            'employee_designation' => $employee->designation ?? '-',
            'employee_email' => $user->email ?? '-',
            'employee_phone' => $user->phone ?? '-',
            // 'employee_photo' => $user->image ?? null // path to photo
        ])->render('backend.employee_dashboard.index');

        // return $dataTable->render('backend.employee_dashboard.index');
    }


    public function startAttendance()
    {
        $today = now()->toDateString();

        $attendance = Attendance::firstOrCreate(
            ['user_id' => auth()->id(), 'date' => $today],
            ['start_time' => now()]
        );

        return response()->json([
            'status' => 'success',
            'message' => $attendance->wasRecentlyCreated ? 'Attendance started successfully.' : 'Attendance already started.'
        ]);
    }

    public function endAttendance()
    {
        $today = now()->toDateString();
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['status' => 'error', 'message' => 'Attendance has not been started yet']);
        }
        if ($attendance->end_time) {
            return response()->json(['status' => 'info', 'message' => 'Attendance already ended']);
        }

        $attendance->update(['end_time' => now()]);

        // Toastr::success('Attendance ended successfully.');
        // return redirect()->back(); // Page reload here
        // return redirect()->back();
        return response()->json(['status' => 'success', 'message' => 'Attendance ended successfully.']);
    }

    public function attendanceStatus()
    {
        $activeAttendance = Attendance::where('user_id', auth()->id())
            ->whereNull('end_time')
            ->first();

        return response()->json([
            'active' => $activeAttendance ? true : false,
            'startTime' => $activeAttendance ? $activeAttendance->start_time->format('h:i A') : null
        ]);
    }
    public function summary(Request $request)
    {
        $userId = Auth::id();
        $type = $request->get('type', 'date');
        $query = Attendance::where('user_id', $userId);

        if ($type === 'date' && $request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($type === 'week' && $request->filled('week')) {
            list($year, $week) = explode('-W', $request->week);
            $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $end = Carbon::now()->setISODate($year, $week)->endOfWeek();
            $query->whereBetween('date', [$start, $end]);
        }

        if ($type === 'month' && $request->filled('month')) {
            $month = Carbon::parse($request->month)->month;
            $year = Carbon::parse($request->month)->year;
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }

        if ($type === 'year' && $request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $calculateHours = function ($attendances) {
            $totalMinutes = $attendances->sum(function ($att) {
                return ($att->start_time && $att->end_time) ? Carbon::parse($att->start_time)->diffInMinutes(Carbon::parse($att->end_time)) : 0;
            });
            $hours = intdiv($totalMinutes, 60);
            $minutes = $totalMinutes % 60;
            return "{$hours}h:{$minutes}m";
        };

        return response()->json([
            'today' => $calculateHours(Attendance::where('user_id', $userId)->whereDate('date', Carbon::today())->get()),
            'week' => $calculateHours(Attendance::where('user_id', $userId)->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()),
            'month' => $calculateHours(Attendance::where('user_id', $userId)->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->get()),
            'year' => $calculateHours(Attendance::where('user_id', $userId)->whereYear('date', Carbon::now()->year)->get()),
            'filtered' => $calculateHours($query->get()),
        ]);
    }
}
