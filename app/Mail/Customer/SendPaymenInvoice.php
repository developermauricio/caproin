<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPaymenInvoice extends Mailable
{
    use Queueable, SerializesModels;
    private $code;
    private $name;
    private $datePayment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $name, $datePayment)
    {
        $this->code = $code;
        $this->name = $name;
        $this->datePayment = $datePayment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(config('app.name').'-'.'RECORDATORIO FECHA DE PAGO FACTURA')
            ->markdown('email.customer.send-payment-generate-invoice')
            ->with('code',$this->code)
            ->with('name',$this->name)
            ->with('datePayment',$this->datePayment);
    }
}
