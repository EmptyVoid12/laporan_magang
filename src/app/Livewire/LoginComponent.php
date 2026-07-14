<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginComponent extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            $role = $user->role;

            if (in_array($role, ['admin', 'operator'], true) || $user->hasRole('super_admin')) {
                Auth::guard('web')->logout();

                throw ValidationException::withMessages([
                    'email' => 'Email atau Password salah',
                ]);
            }

            session()->regenerate();

            if ($role === 'user') {
                return redirect()->intended(route('user.gangguan'));
            }

            if ($role === 'teknisi') {
                return redirect()->intended(route('teknisi.task'));
            }

            Auth::guard('web')->logout();
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau Password salah',
        ]);
    }

    public function render()
    {
        return view('livewire.login-component');
    }
}
