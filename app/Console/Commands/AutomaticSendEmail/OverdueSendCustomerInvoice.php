<?php

namespace App\Console\Commands\AutomaticSendEmail;

use App\Mail\Customer\SendOverdueInvoice;
use App\Mail\Customer\SendPaymenInvoice;
use App\Models\HistorySendPaymetClient;
use App\Models\StateInvoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class OverdueSendCustomerInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:send_overdue_generate_invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Número de días para enviar correo de cobro después de vencida la factura';

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
            ENVIAR CORREO ELECTRÓNICO LUEGO DE VENCERSE LA FACTURA
        =============================================*/
        $rowsCustomers = \App\Models\Customer::query()->with([
            'invoices' => function ($q) {
                return $q->where('state_id', '=', \App\Models\StateInvoice::ID_POR_PAGAR)
                    ->where('send_overdue_cuertomer_state', '=', 0);
            },
            'user'
        ])->whereHas('invoices', function ($q) {
            return $q->where('state_id', '=', \App\Models\StateInvoice::ID_POR_PAGAR)
                ->where('send_overdue_cuertomer_state', '=', 0);
        })->get();

        $finalSendData = $rowsCustomers->map(function ($customer) {
            $invoices = $customer->invoices->filter(function ($invoice) use ($customer) {
                $dateNow = Carbon::now(); //Fecha actual
                $datePayment = Carbon::parse($invoice->expiration_date); // Fecha de vencimiento de la factura
                $diff = $datePayment->diffInDays($dateNow); // Diferencia entre la fecha de hoy y la fecha de vencimiento de la factura
                return $customer->number_of_days_after_invoice_overdue <= $diff && $dateNow->greaterThan($datePayment);
            });
            $sendData = new \stdClass();
            $sendData->user = $customer->user;
            $sendData->invoices = $invoices;
            return $sendData;
        });

        $finalSendData->each(function ($customer) {
            if (count($customer->invoices) > 0) {
                Mail::to($customer->user->email)->send(new SendOverdueInvoice(
                    $customer->invoices,
                    $customer->user
                )); // Envio de correo electrónico

                $customer->invoices->each(function ($invoice) {
                    $invoice->send_overdue_cuertomer_state = 1;
                    $invoice->save(); //Guardamos que hemos enviado el correo electrónico

                    HistorySendPaymetClient::create([
                        'invoice_id' =>  $invoice->id,
                        'type_send' =>  1, //Enviado automaticamente
                        'detail' =>  2, // El detalle es para saber si es recordatorio o vencimiento factura
                    ]);
                });
            }
        });
    }
}
