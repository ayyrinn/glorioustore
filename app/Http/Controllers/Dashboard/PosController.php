<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosController extends Controller
{
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('pos.index', [
            'customers' => Customer::all()->sortBy('name'),
            'productItem' => Cart::instance('pos')->content(),
            'products' => Product::filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    public function addCart(Request $request)
    {
        $rules = [
            'productid' => 'required|string',
            'productname' => 'required|string',
            'price' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::instance('pos')->add([
            'id' => $validatedData['productid'],
            'name' => $validatedData['productname'],
            'qty' => 1,
            'price' => $validatedData['price'],
            'options' => ['size' => 'large']
        ]);

        return Redirect::back()->with('success', 'Product has been added!');

    }

    public function updateCart(Request $request, $rowId)
    {
        $rules = [
            'qty' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::instance('pos')->update($rowId, $validatedData['qty']);

        return Redirect::back()->with('success', 'Cart has been updated!');
    }

    public function deleteCart(String $rowId)
    {
        Cart::instance('pos')->remove($rowId);

        return Redirect::back()->with('success', 'Cart has been deleted!');
    }

    public function createInvoice(Request $request)
    {
        $rules = [
            'custnum' => 'required|numeric', 
        ];

        $validatedData = $request->validate($rules);
        $customer = Customer::where('custnum', $validatedData['custnum'])->first();
        $content = Cart::instance('pos')->content();

        // Retrieve the email of the logged-in user
        $userEmail = Auth::user()->email;
        $employee = Employee::where('email', $userEmail)->first();

        return view('pos.create-invoice', [
            'customer' => $customer,
            'content' => $content,
            'employee' => $employee,
        ]);
    }

    public function printInvoice(Request $request)
    {
        $rules = [
            'customerid' => 'required|exists:customers,customerid',
        ];

        $validatedData = $request->validate($rules);
        $customer = Customer::where('customerid', $validatedData['customerid'])->first();
        $userEmail = Auth::user()->email;
        $employee = Employee::where('email', $userEmail)->first();
        $content = Cart::instance('pos')->content();

        return view('pos.print-invoice', [
            'customer' => $customer,
            'employee' => $employee,
            'content' => $content
        ]);
    }
}
