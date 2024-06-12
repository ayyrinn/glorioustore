<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\Customer;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $customer = Customer::where('custemail', $user->email)->first();

        return view('profile.index', [
            'user' => $user,
            'customer' => $customer, 
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function changePassword(Request $request): View
    {
        $user = $request->user();
        $customer = Customer::where('custemail', $user->email)->first();

        return view('profile.change-password', [
            'user' => $user,
            'customer' => $customer, 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $customer = Customer::where('custemail', $user->email)->first();

        return view('profile.edit', [
            'user' => $user,
            'customer' => $customer, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = User::find(auth()->user()->id);

        $rules = [
            'name' => 'required|max:50',
            'photo' => 'image|file|max:1024',
            'email' => 'required|email|max:50|unique:users,email,'.$user->id,
            'username' => 'required|min:4|max:25|alpha_dash:ascii|unique:users,username,'.$user->id
        ];

        // Rules for customer profile
        $customerRules = [
            'custaddress' => 'nullable|string|max:255',
            'custnum' => 'nullable|string|max:15',
            'custgender' => 'nullable|string|in:M,F',
        ];

        $validatedData = $request->validate($rules + $customerRules);

        if ($validatedData['email'] != $user->email) {
            $validatedData['email_verified_at'] = null;
        }

        /**
         * Handle upload image with Storage.
         */
        if ($file = $request->file('photo')) {
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $path = 'public/profile/';

            $file->storeAs($path, $fileName);
            $validatedData['photo'] = $fileName;
        }

        // Update user information
        $user->update($validatedData);

        // Update customer information if applicable
        if ($user->hasRole('Customer')) {
            $customer = Customer::where('custemail', $validatedData['email'])->first();
            if ($customer) {
                $customerData = [];
                if (isset($validatedData['custaddress'])) {
                    $customerData['custaddress'] = $validatedData['custaddress'];
                }
                if (isset($validatedData['custnum'])) {
                    $customerData['custnum'] = $validatedData['custnum'];
                }
                if (isset($validatedData['custgender'])) {
                    $customerData['custgender'] = $validatedData['custgender'];
                }
                if (isset($validatedData['name'])) {
                    $customerData['custname'] = $validatedData['name'];
                }
                $customer->update($customerData);
            }
        }

        return Redirect::route('profile')->with('success', 'Profile has been updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
