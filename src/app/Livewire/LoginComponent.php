<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $role = Auth::user()->role;
            
            if (in_array($role, ['admin', 'operator']) || Auth::user()->hasRole('super_admin')) {
                Auth::logout();
                return redirect()->to('/login');
            }
            
            if ($role === 'user') {
                return redirect()->to('/user/gangguan');
            } elseif ($role === 'teknisi') {
                return redirect()->to('/teknisi/task');
            }

            return redirect()->to('/');
        } else {
            session()->flash('error', 'Email atau Password salah.');
        }
    }

    public function render()
    {
        return view('livewire.login-component');
    }
}
