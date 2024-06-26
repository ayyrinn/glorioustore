<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class, 'alpha_dash:ascii'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $user->assignRole('customer');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('auth.alamat');
    }

    /**
     * Handle storing of address information.
     */
    public function storeAlamat(Request $request)
    {
        $request->validate([
            'custaddress' => 'required|string|max:255',
            'custnum' => 'required|string|max:15',
        ]);

        $user = Auth::user();

        $customer = Customer::firstOrNew(['custemail' => $user->email]);
        $customer->custname = $user->name;
        $customer->custaddress = $request->custaddress;
        $customer->custnum = $request->custnum;
        $customer->save();

        return redirect()->route('customerdashboard.index')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function showAlamatForm(): View
    {
        $user = Auth::user();
        $customer = Customer::where('custemail', $user->email)->first();
        return view('auth.alamat', compact('customer'));
    }
}
