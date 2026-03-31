<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\AllEmployeeAttendanceDataTable;
use App\DataTables\EmployeeDataTable;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeDataTable $dataTable)
    {
        return $dataTable->render('backend.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.employee.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'designation' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status
        ]);

        // Assign role
        // $user->assignRole('employee');

        // Create employee profile
        Employee::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'designation' => $request->designation,
        ]);
        Toastr::success('Employee created successfully');
        return redirect()->route('admin.employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, AllEmployeeAttendanceDataTable $dataTable)
    {

        // $dataTable->setEmployeeId($id);
        $employee = Employee::findOrFail($id);
        $dataTable->setUserId($employee->user_id);
        return $dataTable->render('backend.employee.show', compact('id', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->user()->delete();
        $employee->delete();
        return response(['status' => 'success', 'message' => 'Delete Successfully!']);
    }
    public function changeStatus(Request $request)
    {
        $employee = Employee::findOrFail($request->id);
        if ($employee->user) {
            $employee->user->status = $request->status === 'true' ? 1 : 0;
            $employee->user->save();
        }

        return response(['message' => 'Status has been Updated!']);
    }
    public function summary(Employee $employee, Request $request)
    {
        $type = $request->get('type', 'date');
        $userId = $employee->user_id;

        // Calculate hours
        $calculateHours = function ($query) {
            $totalMinutes = $query->get()->sum(function ($att) {
                if ($att->start_time && $att->end_time)
                    return Carbon::parse($att->start_time)->diffInMinutes(Carbon::parse($att->end_time));
                return 0;
            });
            return intdiv($totalMinutes, 60) . "h " . ($totalMinutes % 60) . "m";
        };

        // Default Summary (Today, Week, Month, Year)
        $todayQuery = Attendance::where('user_id', $userId)->whereDate('date', Carbon::today());
        $weekQuery = Attendance::where('user_id', $userId)
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        $monthQuery = Attendance::where('user_id', $userId)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year);
        $yearQuery = Attendance::where('user_id', $userId)
            ->whereYear('date', Carbon::now()->year);


        // Filtered Summary
        $filteredQuery = Attendance::where('user_id', $userId);

        // DAY FILTER
        if ($type === 'date' && $request->filled('date')) {
            $filteredQuery->whereDate('date', $request->date);
        }

        // WEEK FILTER 
        if ($type === 'week' && $request->filled('week')) {
            list($year, $week) = explode('-W', $request->week);

            $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $end = Carbon::now()->setISODate($year, $week)->endOfWeek();

            $filteredQuery->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
        }

        // MONTH FILTER
        if ($type === 'month' && $request->filled('month')) {
            $monthObj = Carbon::parse($request->month);
            $filteredQuery->whereMonth('date', $monthObj->month)
                ->whereYear('date', $monthObj->year);
        }

        // YEAR FILTER
        if ($type === 'year' && $request->filled('year')) {
            $filteredQuery->whereYear('date', $request->year);
        }


        return response()->json([
            'today'    => $calculateHours($todayQuery),
            'week'     => $calculateHours($weekQuery),
            'month'    => $calculateHours($monthQuery),
            'year'     => $calculateHours($yearQuery),
            'filtered' => $calculateHours($filteredQuery),
        ]);
    }
}
