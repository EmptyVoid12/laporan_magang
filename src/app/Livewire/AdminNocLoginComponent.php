<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminNocLoginComponent extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function mount()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->to('/adminnoc');
        }
    }

    public function login()
    {
        $this->validate();

        if (Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::guard('admin')->user();
            
            if (in_array($user->role, ['admin', 'operator'], true) || $user->hasRole('super_admin')) {
                return redirect()->to('/adminnoc');
            }

            Auth::guard('admin')->logout();
            session()->flash('error', 'Akses diizinkan hanya untuk Administrator dan Operator.');
        } else {
            session()->flash('error', 'Email atau Password salah.');
        }
    }

    public function render()
    {
        return view('livewire.admin-noc-login-component')
            ->layout('components.layouts.app');
    }
}
