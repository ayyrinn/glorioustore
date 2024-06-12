<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\OnlineTransaction;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\TransactionDetails;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;


class TransactionController extends Controller
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
        
        $transactions = Transaction::where('type', 'offline')->sortable()->paginate($row);

        // return view('transactions.index', [
        //     'transactions' => Transaction::with(['customer', 'employee'])
        //         ->filter(request(['search']))
        //             ->sortable()
        //             ->paginate($row)
        //             ->appends(request()->query()),
        // ]);
        return view('transactions.index', [
            'transactions' => $transactions
        ]);
    }

    public function online()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $onlinetransactions = OnlineTransaction::with(['transaction.customer', 'transaction.employee'])
                                                ->sortable()
                                                ->paginate($row);

        return view('transactions.online', [
            'onlinetransactions' => $onlinetransactions
        ]);
    }



    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     // $customers = Customer::all();
    //     // $employees = Employee::all();
    //     // return view('transactions.create', compact('customers', 'employees'));
    //     return view('transactions.create', [
    //         'customers' => Customer::all(),
    //         'employees' => Employee::all(),
    //     ]);
    // }

    public function storeTransaction(Request $request)
    {
        $transactionid = IdGenerator::generate([
            'table' => 'transactions',
            'field' => 'transactionid',
            'length' => 10,
            'prefix' => 'TR'
        ]);
        
        $rules = [
            'customerid' => 'required|exists:customers,customerid',
            'employeeid' => 'required|exists:employees,employeeid',
            'payment' => 'required|in:CASH,DEBIT,OTHER',
            'pay' => 'numeric|nullable',
        ];

        $validatedData = $request->validate($rules);
        $validatedData['date'] = Carbon::now()->format('Y-m-d');
        $validatedData['transactionid'] = $transactionid;
        $validatedData['type'] = 'OFFLINE';
        $validatedData['total'] = Cart::instance('pos')->total();
        $validatedData['change'] = $validatedData['pay'] ? $validatedData['pay'] - Cart::instance('pos')->total() : 0;
        $validatedData['created_at'] = Carbon::now();

        $customer = Customer::find($validatedData['customerid']);
        $purchaseAmount = $validatedData['total'];

        $pointsEarned = floor($purchaseAmount / 150000);

        $customer->points += $pointsEarned;
        $customer->save();

        $transaction = Transaction::create($validatedData);

        // Create Transaction Details
        $contents = Cart::instance('pos')->content();
        $oDetails = array();

        foreach ($contents as $content) {
            $oDetails['transactionid'] = $transactionid;
            $oDetails['productid'] = $content->id;
            $oDetails['quantity'] = $content->qty;
            $oDetails['price'] = $content->price;
            $oDetails['total'] = $content->subtotal;
            $oDetails['created_at'] = Carbon::now();

            // Use the correct table name here
            TransactionDetails::insert($oDetails);
        }

        // Reduce the stock
        $products = TransactionDetails::where('transactionid', $transactionid)->get();

        foreach ($products as $product) {
            Product::where('productid', $product->productid)
                    ->update(['stock' => DB::raw('stock-'.$product->quantity)]);
        }

        // Clear the shopping cart
        Cart::instance('pos')->destroy();

        return Redirect::route('transaction.index')->with('success', 'Transaction created successfully!');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Transaction $transaction)
    // {
    //     return view('transactions.show', [
    //         'transaction' => $transaction,
    //     ]);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Transaction $transaction)
    // {
    //     // $customers = Customer::all();
    //     // $employees = Employee::all();
    //     // return view('dashboard.transactions.edit', compact('transaction', 'customers', 'employees'));
    //     return view('transactions.edit', [
    //         'customers' => Customer::all(),
    //         'employees' => Employee::all(),
    //         'transaction' => $transaction
    //     ]);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Transaction $transaction)
    // {
    //     $rules = [
    //         'customerid' => 'required|exists:customers,id',
    //         'employeeid' => 'required|exists:employees,id',
    //         'date' => 'required|date',
    //         'payment' => 'required|in:CASH,DEBIT,OTHER',
    //         'type' => 'required|string',
    //         'total' => 'required|numeric',
    //     ];

    //     $validatedData = $request->validate($rules);

    //     Transaction::where('transactionid', $transaction->transactionid)->update($validatedData);

    //     // $transaction->update($request->all());
    //     return Redirect::route('transactions.index')->with('success', 'Transaction updated successfully!');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Transaction $transaction)
    // {
    //     Transaction::destroy($transaction->transactionid);
    //     //$transaction->delete();
    //     return Redirect::route('transactions.index')->with('success', 'Transaction deleted successfully!');
    // }

    /**
     * Display the specified resource.
     */
    public function transactionDetails(string $transactionid)
    {
        $transaction = Transaction::where('transactionid', $transactionid)->first();
        $transactionDetails = TransactionDetails::with('product')
                        ->where('transactionid', $transactionid)
                        ->orderBy('transactionid', 'DESC')
                        ->get();

        return view('transactions.details-transaction', [
            'transaction' => $transaction,
            'transactionDetails' => $transactionDetails,
        ]);
    }

public function transactionOnlineDetails(string $transactionid)
{
    $transaction = Transaction::where('transactionid', $transactionid)->first();

    $onlinetransaction = OnlineTransaction::with(['transaction.customer', 'transaction.employee'])
                        ->where('transactionid', $transactionid)
                        ->first();

    $transactionDetails = TransactionDetails::with('product')
                    ->where('transactionid', $transactionid)
                    ->orderBy('transactionid', 'DESC')
                    ->get();

    return view('transaksionlinecustomer.transactionDetails', [
        'transaction' => $transaction,
        'transactionDetails' => $transactionDetails,
        'onlinetransaction' => $onlinetransaction,
    ]);
}

    
    public function editOnline(string $transactionid)
    {
        $transaction = Transaction::where('transactionid', $transactionid)->first();

        $onlinetransaction = OnlineTransaction::with(['transaction.customer', 'transaction.employee'])
                            ->where('transactionid', $transactionid)
                            ->first();

        $transactionDetails = TransactionDetails::with('product')
                            ->where('transactionid', $transactionid)
                            ->orderBy('transactionid', 'DESC')
                            ->get();

        // Fetch employees with roles KASIR and KURIR
        $employees = Employee::whereIn('role', ['KASIR', 'KURIR'])->get();

        return view('transactions.edit', [
            'onlinetransaction' => $onlinetransaction,
            'transactionDetails' => $transactionDetails,
            'employees' => $employees,
            'transaction' => $transaction,
        ]);
    }

    public function updateOnlineCashier(Request $request)
    {
        $request->validate([
            'employeeid' => 'required|string',
            'transactionid' => 'required|string',
        ]);
    
        // Perbarui kasir untuk transaksi yang sesuai
        Transaction::where('transactionid', $request->transactionid)
            ->update(['employeeid' => $request->employeeid]);
    
        return redirect()->back()->with('success', 'Cashier has been updated successfully!');
    }
    
    public function updateOnlineCourier(Request $request)
    {
        $request->validate([
            'courierid' => 'required|string',
            'transactionid' => 'required|string',
        ]);
    
        // Perbarui kurir untuk transaksi online yang sesuai
        OnlineTransaction::where('transactionid', $request->transactionid)
            ->update(['courierid' => $request->courierid]);
    
        return redirect()->back()->with('success', 'Courier has been updated successfully!');
    }
    

    /**
     * Update status of online transaction.
     */
    public function updateStatusOnline(Request $request)
    {
        $request->validate([
            'transactionid' => 'required|exists:onlinetransactions,transactionid',
            'status' => 'required|in:DIKEMAS,DIKIRIM,DITERIMA,DIBATALKAN',
        ]);

        OnlineTransaction::where('transactionid', $request->transactionid)
            ->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Transaction status updated successfully!');
    }

    public function cancelStatusOnline(Request $request)
    {
        $request->validate([
            'transactionid' => 'required|exists:onlinetransactions,transactionid',
            'status' => 'required|in:DIKEMAS,DIKIRIM,DITERIMA,DIBATALKAN',
        ]);

        OnlineTransaction::where('transactionid', $request->transactionid)
            ->update(['status' => $request->status]);

        return Redirect::route('transaction.online')->with('error', 'Transaction has been canceled!');
    }

    public function completeOnline(Request $request)
    {
        $request->validate([
            'transactionid' => 'required|exists:onlinetransactions,transactionid',
            'status' => 'required|in:DIKEMAS,DIKIRIM,DITERIMA,DIBATALKAN',
            'proofdelivery' => 'required|image|max:1024',
        ]);
        
        $transactionid = $request->transactionid;

        // Handle upload image with Storage
        if ($request->hasFile('proofdelivery')) {
            $file = $request->file('proofdelivery');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = 'public/transaction/delivery/';
            $file->storeAs($path, $fileName);

            // Update OnlineTransaction with proofdelivery file name
            OnlineTransaction::where('transactionid', $transactionid)
                ->update(['proofdelivery' => $fileName, 'status' => $request->status]);
        }

        // Reduce the stock
        $products = TransactionDetails::where('transactionid', $transactionid)->get();

        foreach ($products as $product) {
            Product::where('productid', $product->productid)
                    ->update(['stock' => DB::raw('stock-'.$product->quantity)]);
        }

        OnlineTransaction::where('transactionid', $request->transactionid)
            ->update(['status' => $request->status]);

        // Fetch the total purchase amount from the online transaction
        $totalPurchaseAmount = Transaction::where('transactionid', $transactionid)->value('total');

        // Calculate points based on total purchase amount
        $pointsEarned = floor($totalPurchaseAmount / 150000);

        // Retrieve the customer ID associated with this online transaction
        $customerId = Transaction::where('transactionid', $transactionid)->value('customerid');

        // Update customer's points
        $customer = Customer::find($customerId);
        $customer->points += $pointsEarned;
        $customer->save();
        
        return Redirect::route('transaction.online')->with('success', 'Transaction has been completed!');
    }

    public function invoiceDownload(string $transactionid)
    {
        $transaction = Transaction::where('transactionid', $transactionid)->first();
        $transactionDetails = TransactionDetails::with('product')
                        ->where('transactionid', $transactionid)
                        ->orderBy('transactionid', 'DESC')
                        ->get();

        // show data (only for debugging)
        return view('transactions.invoice-transaction', [
            'transaction' => $transaction,
            'transactionDetails' => $transactionDetails,
        ]);
    }

    public function createOnlineOrder(Request $request)
    {
        // Assuming the customer ID is being sent as a parameter in the request
        $customerId = $request->input('customerid');

        // Retrieve customer data
        $customer = Customer::find($customerId);

        // If customer is not found, handle this scenario accordingly
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer not found');
        }

        // Get products from the shopping cart
        $cartItems = Cart::content();

        // Check if the cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang anda kosong, silakan masukkan produk sebelum transaksi');
        }

        // If there are items in the cart, proceed to the view
        return view('transaksionlinecustomer.index', [
            'cartItems' => $cartItems,
            'total' => Cart::total(),
            'customer' => $customer,
        ]);
    }

    
    
    public function storeOnlineOrder(Request $request)
    {
        $transactionid = IdGenerator::generate([
            'table' => 'transactions',
            'field' => 'transactionid',
            'length' => 10,
            'prefix' => 'TR'
        ]);
        
        $rules = [
            'customerid' => 'required|exists:customers,customerid',
            'payment' => 'required|in:CASH,DEBIT,OTHER',
            // 'pay' => 'numeric|nullable',
        ];
        
        $validatedData = $request->validate($rules);
        $validatedData['date'] = Carbon::now()->format('Y-m-d');
        $validatedData['transactionid'] = $transactionid;
        $validatedData['employeeid'] = 'EM001';
        $validatedData['type'] = 'ONLINE';
        $validatedData['total'] = Cart::total() + 10000;
        $validatedData['created_at'] = Carbon::now();
        
        $transaction = Transaction::create($validatedData);
        
        // Create Transaction Details
        $contents = Cart::content();
        $oDetails = [];
        
        foreach ($contents as $content) {
            $oDetails[] = [
                'transactionid' => $transactionid,
                'productid' => $content->id,
                'quantity' => $content->qty,
                'price' => $content->price,
                'total' => $content->subtotal,
                'created_at' => Carbon::now()
            ];
        }
        
        TransactionDetails::insert($oDetails);
        
        // Reduce the stock
        foreach ($oDetails as $detail) {
            $product = Product::find($detail['productid']);
            $product->stock -= $detail['quantity'];
            $product->save();
        }
        
        // Set the transaction status based on payment method
        $status = 'BELUM BAYAR';
        if ($validatedData['payment'] === 'CASH') {
            $status = 'DIKEMAS';
        }

        OnlineTransaction::create([
            'transactionid' => $transactionid,
            'courierid' => null,
            'status' => $status
        ]);

        // Clear the shopping 
        Cart::destroy();

        // Determine the redirect based on payment method
        if ($validatedData['payment'] === 'CASH') {
            return redirect()->route('transaksi.showOrderHistory')->with('success', 'Online order placed successfully!');
        } else {
            return view('transaksionlinecustomer.payment', ['transaction' => $transaction]);
        }
    }

    public function showOrderHistory(Request $request)
    {
        // Retrieve the authenticated user's email
        $userEmail = auth()->user()->email;

        // Retrieve the customer ID from the customer table using the user's email
        $customer = Customer::where('custemail', $userEmail)->first();

        // Check if customer exists
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer not found.');
        }

        // Retrieve the order history data with status from the database for the logged-in customer
        $transactions = Transaction::select('transactions.*', 'onlinetransactions.status')
                        ->join('onlinetransactions', 'transactions.transactionid', '=', 'onlinetransactions.transactionid')
                        ->where('transactions.customerid', $customer->customerid)
                        ->when($request->search, function($query) use ($request) {
                            $query->where('transactions.transactionid', 'like', '%' . $request->search . '%');
                        })
                        ->paginate($request->row ?? 10);

        // Pass the data to the view
        return view('transaksionlinecustomer.transaksionlinehistory', ['transactions' => $transactions]);
    }


    // public function onlineDetail()
    // {
    //     // Retrieve cart items and total from the shopping cart
    //     $cartItems = Cart::content();
    //     $total = Cart::total();

    //     // Fetch product information for each cart item
    //     foreach ($cartItems as $item) {
    //         // Assuming each cart item has a product_id
    //         $item->product = Product::find($item->id);
    //     }

    //     // Pass the $cartItems and $total variables to the view
    //     return view('transaksionlinecustomer.index', [
    //         'cartItems' => $cartItems,
    //         'total' => $total,
    //     ]);
    // }

    public function payment(string $transactionid)
    {
        $transaction = Transaction::where('transactionid', $transactionid)->firstOrFail(); 
        return view('transaksionlinecustomer.payment', [
            'transaction' => $transaction,
        ]);
    }


    public function storePayment(Request $request)
    {
        $request->validate([
            'transactionid' => 'required|string',
            'proofpayment' => 'required|image|max:1024',
        ]);
        
        // Handle upload image with Storage
        if ($request->hasFile('proofpayment')) {
            $file = $request->file('proofpayment');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = 'public/transaction/payment/';
            $file->storeAs($path, $fileName);

            // Update OnlineTransaction with proofpayment file name
            OnlineTransaction::where('transactionid', $request->transactionid)
                ->update(['proofpayment' => $fileName, 'status' => 'DIKEMAS']);
        }

        return Redirect::route('transaksi.showOrderHistory')->with('success', 'Pembayaran berhasil! Pesanan anda sedang diproses.');
    }
}