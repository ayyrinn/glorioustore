<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }
        
        return view('orders.index', [
            'orders' => Order::with(['supplier'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    public function create()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('orders.create', [
            'suppliers' => Supplier::all()->sortBy('supname'),
            'productItem' => Cart::instance('supplier')->content(),
            'products' => Product::filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    
    /**
     * Display a listing of the resource.
     */
    public function pendingOrders()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('order_status', 'pending')->sortable()->paginate($row);

        return view('orders.pending-orders', [
            'orders' => $orders
        ]);
    }

    public function completeOrders()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('order_status', 'complete')->sortable()->paginate($row);

        return view('orders.complete-orders', [
            'orders' => $orders
        ]);
    }

    // public function stockManage()
    // {
    //     $row = (int) request('row', 10);

    //     if ($row < 1 || $row > 100) {
    //         abort(400, 'The per-page parameter must be an integer between 1 and 100.');
    //     }

    //     return view('stock.index', [
    //         'products' => Product::with(['category', 'supplier'])
    //             ->filter(request(['search']))
    //             ->sortable()
    //             ->paginate($row)
    //             ->appends(request()->query()),
    //     ]);
    // }
    public function addCart(Request $request)
    {
        $rules = [
            'productid' => 'required|string',
            'productname' => 'required|string',
            'price' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::instance('supplier')->add([
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
            'qty' => 'nullable|numeric',
            'price' => 'nullable|numeric',
        ];

        $validatedData = $request->validate($rules);

        if (isset($validatedData['qty'])) {
            Cart::instance('supplier')->update($rowId, ['qty' => $validatedData['qty']]);
        }
    
        if (isset($validatedData['price'])) {
            Cart::instance('supplier')->update($rowId, ['price' => $validatedData['price']]);
        }

        return Redirect::back()->with('success', 'Cart has been updated!');
    }

    public function deleteCart(String $rowId)
    {
        Cart::instance('supplier')->remove($rowId);

        return Redirect::back()->with('success', 'Cart has been deleted!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrder(Request $request)
    {
        $orderid = IdGenerator::generate([
            'table' => 'orders',
            'field' => 'orderid',
            'length' => 7,
            'prefix' => 'OR'
        ]);
        
        $rules = [
            'supplierid' => 'required|exists:suppliers,supplierid',
        ];

        $validatedData = $request->validate($rules);
        $validatedData['date'] = Carbon::now()->format('Y-m-d');
        $validatedData['status'] = 'BELUM DITERIMA';
        $validatedData['orderid'] = $orderid;
        $validatedData['created_at'] = Carbon::now();

        $order = Order::insertGetId($validatedData);

        // Create Order Details
        $contents = Cart::instance('supplier')->content();
        $oDetails = array();

        foreach ($contents as $content) {
            $oDetails['orderid'] = $orderid;
            $oDetails['productid'] = $content->id;
            $oDetails['qty'] = $content->qty;
            $oDetails['buying_price'] = $content->price;
            $oDetails['subtotal'] = (int) $content->subtotal;
            $oDetails['created_at'] = Carbon::now();

            OrderDetails::insert($oDetails);
        }

        Cart::instance('supplier')->destroy();

        return Redirect::route('order.index')->with('success', 'Order has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function orderDetails(string $orderid)
    {
        $order = Order::where('orderid', $orderid)->first();
        $orderDetails = OrderDetails::with('product')
                        ->where('orderid', $orderid)
                        ->orderBy('orderid', 'DESC')
                        ->get();

        return view('orders.details', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request)
    {
        $orderid = $request->orderid;

        // Reduce the stock
        $products = OrderDetails::where('orderid', $orderid)->get();

        foreach ($products as $product) {
            Product::where('productid', $product->productid)
                    ->update(['stock' => DB::raw('stock+'.$product->qty)]);
        }

        Order::findOrFail($orderid)->update(['status' => 'DITERIMA']);

        return Redirect::route('order.index')->with('success', 'Order has been completed!');
    }

    public function createInvoice(Request $request)
    {
        $rules = [
            'supplierid' => 'required|string', 
        ];

        $validatedData = $request->validate($rules);
        $supplier = Supplier::where('supplierid', $validatedData['supplierid'])->first();
        $content = Cart::instance('supplier')->content();

        return view('orders.create-invoice', [
            'content' => $content,
            'supplier' => $supplier,
        ]);
    }

    // public function invoiceDownload(Int $order_id)
    // {
    //     $order = Order::where('id', $order_id)->first();
    //     $orderDetails = OrderDetails::with('product')
    //                     ->where('order_id', $order_id)
    //                     ->orderBy('id', 'DESC')
    //                     ->get();

    //     // show data (only for debugging)
    //     return view('orders.invoice-order', [
    //         'order' => $order,
    //         'orderDetails' => $orderDetails,
    //     ]);
    // }

    // public function pendingDue()
    // {
    //     $row = (int) request('row', 10);

    //     if ($row < 1 || $row > 100) {
    //         abort(400, 'The per-page parameter must be an integer between 1 and 100.');
    //     }

    //     $orders = Order::where('due', '>', '0')
    //         ->sortable()
    //         ->paginate($row);

    //     return view('orders.pending-due', [
    //         'orders' => $orders
    //     ]);
    // }

    // public function orderDueAjax(Int $id)
    // {
    //     $order = Order::findOrFail($id);

    //     return response()->json($order);
    // }

    // public function updateDue(Request $request)
    // {
    //     $rules = [
    //         'order_id' => 'required|numeric',
    //         'due' => 'required|numeric',
    //     ];

    //     $validatedData = $request->validate($rules);

    //     $order = Order::findOrFail($request->order_id);
    //     $mainPay = $order->pay;
    //     $mainDue = $order->due;

    //     $paid_due = $mainDue - $validatedData['due'];
    //     $paid_pay = $mainPay + $validatedData['due'];

    //     Order::findOrFail($request->order_id)->update([
    //         'due' => $paid_due,
    //         'pay' => $paid_pay,
    //     ]);

    //     return Redirect::route('order.pendingDue')->with('success', 'Due Amount Updated Successfully!');
    // }
}
