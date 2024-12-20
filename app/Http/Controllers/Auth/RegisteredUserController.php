<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'nickname' => ['required', 'string', 'max:255', 'unique:users'], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'firstname' => ['nullable', 'string', 'max:255'], 
            'lastname' => ['nullable', 'string', 'max:255'], 
            'age' => ['nullable', 'integer', 'min:0', 'max:255'], 
            'nationality' => ['nullable', 'string', 'max:191'], 
            'role' => ['nullable', 'string', 'in:user,coach,mod'],
            'avatar' => ['nullable', 'string'], 
        ]);

        $user = User::create([
            'nickname' => $request->input('nickname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Le mot de passe doit être hashé
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'age' => $request->input('age'),
            'nationality' => $request->input('nationality'),
            'role' => $request->input('role'),
            'avatar' => $request->input('avatar'),
        ]);


        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
