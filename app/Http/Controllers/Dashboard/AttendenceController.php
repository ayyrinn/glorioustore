<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Employee;
use App\Models\Attendence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class AttendenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $attendences = Attendence::sortable()
        ->select('date')
        ->groupBy('date')
        ->orderBy('date', 'desc');

        // Apply additional filters if needed, such as date
        if (request()->has('date')) {
            $attendences->whereDate('date', request('date'));
        }

        // Paginate the results and append query parameters
        $attendences = $attendences->paginate($row)->appends(request()->query());

        return view('attendence.index', compact('attendences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attendence.create', [
            'employees' => Employee::all()->sortBy('name'),
        ]);
    }

    public function show(Attendence $attendence)
    {
        return view('attendence.show', [
            'attendences' => Attendence::with(['employee'])->where('date', $attendence->date)->get(),
            'date' => $attendence->date
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $countEmployee = count($request->employeeid);
        $rules = [
            'date' => 'required|date_format:Y-m-d|max:10',
        ];

        $validatedData = $request->validate($rules);

        // Delete if the date is already created (it is just for updating new attendance). If not it will create new attendance
        Attendence::where('date', $validatedData['date'])->delete();

        for ($i=1; $i <= $countEmployee; $i++) {
            $status = 'status' . $i;
            $attend = new Attendence();

            $attend->date = $validatedData['date'];
            $attend->employeeid = $request->employeeid[$i];
            $attend->status = $request->$status;

            $attend->save();
        }

        return Redirect::route('attendence.index')->with('success', 'Attendence has been Created!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendence $attendence)
    {
        return view('attendence.edit', [
            'attendences' => Attendence::with(['employee'])->where('date', $attendence->date)->get(),
            'date' => $attendence->date
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendence $attendence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendence $attendence)
    {
        //
    }
}
