<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCustomer extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $password;
    private $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password, $email)
    {
        $this->user = $user;
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(config('app.name').'-'.'TUS CREDENCIALES DE ACCESO')
            ->markdown('email.customer.new-customer')
            ->with('user',$this->user)
            ->with('password',$this->password)
            ->with('email',$this->email);
    }
}
