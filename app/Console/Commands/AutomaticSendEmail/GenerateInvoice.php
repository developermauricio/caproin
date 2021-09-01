<?php

namespace App\Console\Commands\AutomaticSendEmail;

use App\Mail\Customer\SendPaymenInvoice;
use App\Models\HistorySendPaymetClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GenerateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:send_payment_generate_invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Número de días para enviar cobro por correo electrónico, después de generar la factura';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*=============================================
            ENVIAR CORREO ELECTRÓNICO AUTOMATICOS RECORDATORIO FECHA DE PAGO
        =============================================*/
        $rowsCustomers = \App\Models\Customer::query()->with('invoices.state', 'user')->get();

        foreach ($rowsCustomers as $row) {
            if (count($row->invoices) > 0) {
                foreach ($row->invoices as $invoice) {
                    $dateNow = Carbon::now()->toDateString(); //Fecha actual
                    $datePayment = Carbon::parse($invoice->date_payment_client); // Fecha de pago por parte del cliente
                    $diff = $datePayment->diffInDays($dateNow); // Diferencia entre la fecha de hoy y la fecha de pago por parte del cliente

                    if ($row->number_of_days_after_generating_invoice === $diff && $invoice->send_payment_cuertomer_state === 0 && $invoice->state->id === 1) {
                        Mail::to($row->user->email)->send(new SendPaymenInvoice(
                            $invoice->invoice_number,
                            $row->user->name,
                            $invoice->date_payment_client
                        ));// Envio de correo electrónico
                        $invoice->send_payment_cuertomer_state = 1;
                        $invoice->save(); //Guardamos que hemos enviado el correo electrónico

                        HistorySendPaymetClient::create([
                            'invoice_id' =>  $invoice->id,
                            'type_send' =>  1, //Enviado automaticamente
                            'detail' =>  1, // El detalle es envio para recordatorio de pago
                        ]);
                    }
                }
            }
        }
    }
}
