<?php

namespace App\Mail\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOverdueInvoice extends Mailable
{
    use Queueable, SerializesModels;

    private $invoices;
    private $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoices, $user)
    {
        $this->invoices = $invoices;
        $this->user = $user;
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
            ->with('invoices',$this->invoices)
            ->with('user',$this->user);
    }
}
