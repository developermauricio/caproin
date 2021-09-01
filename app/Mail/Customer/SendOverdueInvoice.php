<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOverdueInvoice extends Mailable
{
    use Queueable, SerializesModels;

    private $code;
    private $name;
    private $dateExpiration;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $name, $dateExpiration)
    {
        $this->code = $code;
        $this->name = $name;
        $this->dateExpiration = $dateExpiration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(config('app.name').'-'.'RECORDATORIO FACTURA VENCIDA')
            ->markdown('email.customer.send-overdue-generate-invoice')
            ->with('code',$this->code)
            ->with('name',$this->name)
            ->with('dateExpiration',$this->dateExpiration);
    }
}
