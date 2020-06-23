<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Store\Models\Customer;
use Store\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = [
            'email' => request('email'),
            'password' => request('password'),
        ];

        if (! Auth::attempt($credentials)) {
            return redirect()->back()->with('Wrong email or password.');
        }

        /** @var User $user */
        $user = Auth::user();

        return redirect($user->loginRedirectROute());
    }

    public function register()
    {
        /** @var Customer $user */
        $user = Customer::query()->create([
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        Auth::login($user, true);

        return redirect($user->loginRedirectRoute());
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
