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
        $rowsCustomers = \App\Models\Customer::query()->with([
            'invoices' => function ($q) {
                return $q->where('state_id', '=', \App\Models\StateInvoice::ID_POR_PAGAR)
                    ->where('send_payment_cuertomer_state', '=', 0);
            },
            'user'
        ])->whereHas('invoices', function ($q) {
            return $q->where('state_id', '=', \App\Models\StateInvoice::ID_POR_PAGAR)
                ->where('send_payment_cuertomer_state', '=', 0);
        })->get();

        $finalSendData = $rowsCustomers->map(function ($customer) {
            $invoices = $customer->invoices->filter(function ($invoice) use ($customer) {
                $dateNow = Carbon::now(); //Fecha actual
                $datePayment = Carbon::parse($invoice->date_payment_client); // Fecha de pago por parte del cliente
                $diff = $datePayment->diffInDays($dateNow); // Diferencia entre la fecha de hoy y la fecha de pago por parte del cliente
                return $customer->number_of_days_after_generating_invoice >= $diff && !$dateNow->greaterThan($datePayment);
            });
            $sendData = new \stdClass();
            $sendData->user = $customer->user;
            $sendData->invoices = $invoices;
            return $sendData;
        });


        $finalSendData->each(function ($customer){
            if (count($customer->invoices) > 0) {

                Mail::to($customer->user->email)->send(new SendPaymenInvoice(
                    $customer->invoices,
                    $customer->user
                )); // Envio de correo electrónico

                $customer->invoices->each(function ($invoice) {
                    $invoice->send_payment_cuertomer_state = 1;
                    $invoice->save(); //Guardamos que hemos enviado el correo electrónico

                    HistorySendPaymetClient::create([
                        'invoice_id' =>  $invoice->id,
                        'type_send' =>  1, //Enviado automaticamente
                        'detail' =>  1, // El detalle es envio para recordatorio de pago
                    ]);
                });
            }
        });
    }
}
