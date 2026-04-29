<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPasswordComponent extends Component
{
    public $email;
    public $statusMessage = '';

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected $messages = [
        'email.exists' => 'Email tidak terdaftar di sistem kami.',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::broker()->sendResetLink(['email' => $this->email]);

        if ($status == Password::RESET_LINK_SENT) {
            $this->statusMessage = 'Link reset password telah dikirim ke email Anda.';
        } else {
            session()->flash('error', __($status));
        }
    }

    public function render()
    {
        return view('livewire.forgot-password-component');
    }
}
