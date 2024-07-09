<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use DateTime;

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
            'name' => ['nullable', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'min:8', 'max:24', 'confirmed', Rules\Password::defaults()],
            'birth_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    $minAge = 18;
                    $birthdate = new DateTime($value);
                    $now = new DateTime();
                    $age = $now->diff($birthdate)->y;

                    if ($age < $minAge) {
                        $fail("Devi avere almeno {$minAge} anni.");
                    }
                },
            ],
        ], [
            'email.required' => 'Inserisci una email.',
            'email.max' => 'Puoi usare massimo :max caratteri.',
            'password.min' => 'La password deve avere almeno :min caratteri.',
            'password.required' => 'Inserisci una Password.',
            'password.max' => 'La password non può superare :max caratteri.',
            'birth_date.nullable' => 'Inserisci una data di nascita valida.',
            'birth_date.date' => 'Inserisci una data di nascita valida.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
