<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StateInvoice extends Model
{
    const ID_POR_PAGAR = 1;
    const ID_PAGADO = 2;
    const ID_RETRASADO = 3;
}
