<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class SupplierController extends Controller
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
        
        return view('suppliers.index', [
            'suppliers' => Supplier::filter(request(['search']))->sortable()->paginate($row)->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $supplierid = IdGenerator::generate([
            'table' => 'suppliers',
            'field' => 'supplierid',
            'length' => 5,
            'prefix' => 'SP'
        ]);

        $rules = [
            'supname' => 'required|string|max:255',
            'supaddress' => 'required|string|max:255',
            'supnumber' => 'required|string|max:15',
        ];

        $validatedData = $request->validate($rules);

        // save supplier code value
        $validatedData['supplierid'] = $supplierid;

        Supplier::create($validatedData);

        return Redirect::route('suppliers.index')->with('success', 'Supplier has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', [
            'supplier' => $supplier,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', [
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $rules = [
            'supname' => 'required|string|max:255',
            'supaddress' => 'required|string|max:255',
            'supnumber' => 'required|string|max:15',
        ];

        $validatedData = $request->validate($rules);

        Supplier::where('supplierid', $supplier->supplierid)->update($validatedData);

        return Redirect::route('suppliers.index')->with('success', 'Supplier has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        Supplier::destroy($supplier->supplierid);

        return Redirect::route('suppliers.index')->with('success', 'Supplier has been deleted!');
    }
}
