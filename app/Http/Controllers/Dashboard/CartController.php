<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function index()
    {
        // Get the authenticated user's email
        $userEmail = auth()->user()->email;

        // Retrieve the customer with the matching email
        $customer = Customer::where('custemail', $userEmail)->first();

        // Check if a customer with the given email exists
        if ($customer) {
            // Retrieve the customer ID
            $customerId = $customer->customerid;

            // Retrieve cart items
            $cartItems = Cart::content();
            
            // Fetch product information for each cart item
            foreach ($cartItems as $item) {
                // Assuming each cart item has a product_id
                $item->product = Product::find($item->id);
            }

            // Pass both $cartItems and $customerId to the view
            return view('productdetailcustomer.keranjangcustomer.index', compact('cartItems', 'customerId'));
        } else {
            // Handle the case when no customer with the given email is found
            return redirect()->back()->with('error', 'Customer not found');
        }
    }

    public function addCart(Request $request)
{
    // Retrieve the product from the database
    $product = Product::find($request->productid);
    
    if (!$product) {
        return Redirect::back()->withErrors(['Product tidak ditemukan.']);
    }
    
    $rules = [
        'productid' => 'required|string',
        'productname' => 'required|string',
        'price' => 'required|numeric',
        'qty' => 'nullable|integer|min:1|max:' . $product->stock,
    ];

    $validatedData = $request->validate($rules);

    // Set qty to 1 if it is not provided
    if (empty($validatedData['qty'])) {
        $validatedData['qty'] = 1;
    }

    Cart::add([
        'id' => $validatedData['productid'],
        'name' => $validatedData['productname'],
        'qty' => $validatedData['qty'],
        'price' => $validatedData['price'],
    ]);

    // Pass the $product variable to the view
    return Redirect::back()->with('success', 'Produk berhasil ditambahkan');
}



public function updateCart(Request $request, $rowId)
{
    $rules = [
        'qty' => 'required|numeric|min:1',
    ];

    $validatedData = $request->validate($rules);

    Cart::update($rowId, $validatedData['qty']);

    return Redirect::back()->with('success', 'Keranjang berhasil di-update!');
}


    public function deleteCart(String $rowId)
    {
        Cart::remove($rowId);
        return Redirect::back()->with('success', 'Keranjang berhasil dihapus!');
    }
}
