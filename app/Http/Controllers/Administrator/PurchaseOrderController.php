<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Conveyor;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\OrderDetail;
use App\Models\OrderType;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderStateHistory;
use App\Models\StateOrder;
use App\Models\TypeProduct;
use App\Models\Zone;
use App\Traits\MessagesException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use SebastianBergmann\Environment\Console;

class PurchaseOrderController extends Controller
{
    use MessagesException;

    public function index()
    {
        // Tipo de orden, Zona, Transportista, Tipo Pago, y el Tipo de Moneda
        $order_types = OrderType::all(['id', 'name']);
        $zones = Zone::all(['id', 'name']);
        $conveyors = Conveyor::all(['id', 'name']);
        $payment_types = PaymentType::all(['id', 'name']);
        $currencies = Currency::all(['id', 'code']);

        return view('admin.purchase-order.list-purchase-orders', compact('order_types', 'zones', 'conveyors', 'payment_types', 'currencies'));
    }

    public function create()
    {
        return view('admin.purchase-order.create-purchase-order');
    }

    public function update($id)
    {
        return view('admin.purchase-order.update-purchase-order', compact('id'));
    }

    public function getApiPurchaseOrdes()
    {
        $rol = auth()->user()->roles->first()->name; // Rol del Usuario
        $user = auth()->user()->id; // id del Usuario

        $purchase_ordes = PurchaseOrder::with([
            'customer',
            'purchase_order_state_histories.state_order'
        ]);

        if ($rol === "Vendedor") {
            $purchase_ordes->whereHas('seller', function ($q) use ($user) {
                return $q->where('user_id', $user);
            });
        } elseif ($rol === "Cliente") {
            $purchase_ordes->whereHas('customer', function ($q) use ($user) {
                return $q->where('user_id', $user)->orWhereHas('sede', function ($q) use ($user) {
                    return $q->where('user_id', $user);
                });
            });
        }

        $purchase_ordes = $purchase_ordes->get();
        return datatables()->of($purchase_ordes)->toJson();
    }

    public function getAllCustomers()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function getAllInvoices()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    public function getAllOrderTypes()
    {
        $orderTypes = OrderType::all();
        return response()->json($orderTypes);
    }

    public function getAllStateOrders()
    {
        $stateOrders = StateOrder::all();
        return response()->json($stateOrders);
    }

    public function getAllProductTypes()
    {
        $productTypes = TypeProduct::all();
        return $productTypes;
    }

    public function getAllZone()
    {
        $zones = Zone::all();
        return response()->json($zones);
    }

    public function getAllSeller()
    {
        $sellers = Employee::with('user')->whereHas('user.roles', function ($q) {
            return $q->where("name", User::ROL_VENDEDOR);
        })->get();
        return response()->json($sellers);
    }

    public function getAllTypeCoin()
    {
        $typeCoins = Currency::all();
        return response()->json($typeCoins);
    }

    public function getAllConveyor()
    {
        $conveyors = Conveyor::all();
        return response()->json($conveyors);
    }

    public function getAllProductByType(Request $request)
    {
        $products = Product::with('productPrices')->where('type_products_id', $request->input('type'))->get();
        return $products;
    }

    public function getAllSeguimiento(Request $request)
    {
        $seguimientos = Comment::with('commentable.state_order')
            ->whereByIn(PurchaseOrderStateHistory::class, collect($request->input('ids')))
            ->orderBy('created_at', 'DESC')
            ->get();
        return $seguimientos;
    }

    public function saveNewSeguimiento(Request $request)
    {
        $commentable = $request->get('commentable');
        $comment = new Comment();
        $comment->title = $request->get('title');
        $comment->body = $request->get('body');
        $stateHistory = PurchaseOrderStateHistory::find($commentable['id']);
        $stateHistory->comments()->save($comment);

        return response()->json($comment, 201);
    }

    public function getPurchaseOrderById($id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'order_type',
            'customer',
            'zone',
            'seller.user',
            'currency',
            'conveyor',
            'payment',
            'invoice.state',
            'order_details' => function ($q) {
                return $q->with('product.productPrices', 'currency');
            },
            'purchase_order_state_histories.state_order'
        ])->find($id);

        return response()->json($purchaseOrder);
    }

    public function savePurchaseOrder(Request $request)
    {

        try {
            DB::beginTransaction();
            $purchaseOrder = new PurchaseOrder();
            $this->storePurchaseOrder($purchaseOrder, $request);
            $this->updateStateHistory($purchaseOrder, $request);
            $this->updateOrderDetails($purchaseOrder, $request);
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => $th->getMessage(),
                'error' => $th->getTrace()
            ], 500);
        }
    }

    public function updatePurchaseOrder($id, Request $request)
    {
        $rol = auth()->user()->roles->first()->name;
        if ($rol === 'Logistica') {
            return $this->updatePurchaseOrderLogistic($id, $request);
        }
        if ($rol !== 'Administrador') {
            return response()->json([], 405);
        }
        try {
            DB::beginTransaction();
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $this->storePurchaseOrder($purchaseOrder, $request);
            $this->updateStateHistory($purchaseOrder, $request);
            $this->updateOrderDetails($purchaseOrder, $request);
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => $th->getMessage(),
                'error' => $th->getTrace()
            ], 500);
        }
    }

    public function updatePurchaseOrderLogistic($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $this->updateConveyorPurchaseOrder($purchaseOrder, $request);
            $this->updateStateHistory($purchaseOrder, $request);
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => $th->getMessage(),
                'error' => $th->getTrace()
            ], 500);
        }
    }

    private function updateConveyorPurchaseOrder(&$purchaseOrder, $request)
    {
        try {
            $purchaseOrder->dispatch_guide_number = $request->input('dispatch_guide_number');
            if ($request->input('conveyor')){
                $purchaseOrder->conveyor_id = $request->input('conveyor.id');
            }
            $purchaseOrder->total_delivery = $request->input('total_delivery');
            $purchaseOrder->actual_dispatch_date = $this->getDate($request->input('actual_dispatch_date'));
            $purchaseOrder->actual_delivery_date = $this->getDate($request->input('actual_delivery_date'));
            $purchaseOrder->contact_number = $request->input('contact_number');

            $purchaseOrder->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    private function storePurchaseOrder(&$purchaseOrder, $request)
    {
        try {
            $purchaseOrder->customer_order_number = $request->input('customer_order_number');
            $purchaseOrder->internal_order_number = $request->input('internal_order_number');
            $purchaseOrder->final_user = $request->input('final_user');

            $purchaseOrder->customer_id = $request->input('customer.id');
            $purchaseOrder->order_type_id = $request->input('order_type.id');
            $purchaseOrder->zone_id = $request->input('zone.id');
            $purchaseOrder->seller_id = $request->input('seller.id');

            $purchaseOrder->house = $request->input('house');
            $purchaseOrder->description = $request->input('description');
            // $purchaseOrder->has_blueprint = $request->input('has_blueprint');

            $purchaseOrder->currency_id = $request->input('currency.id');
            $purchaseOrder->total_value = $request->input('total_value');
            $purchaseOrder->trm = $request->input('trm');

            $purchaseOrder->internal_quote_number = $request->input('internal_quote_number');
            $purchaseOrder->manufacturer_house_quotation_number = $request->input('manufacturer_house_quotation_number');
            $purchaseOrder->dispatch_guide_number = $request->input('dispatch_guide_number');

            if ($request->input('conveyor')){
                $purchaseOrder->conveyor_id = $request->input('conveyor.id');
            }
            $purchaseOrder->total_delivery = $request->input('total_delivery');

            $purchaseOrder->order_receipt_date = $this->getDate($request->input('order_receipt_date'));
            $purchaseOrder->offer_delivery_date = $this->getDate($request->input('offer_delivery_date'));
            $purchaseOrder->delivery_date_required_customer = $this->getDate($request->input('delivery_date_required_customer'));
            $purchaseOrder->expected_dispatch_date = $this->getDate($request->input('expected_dispatch_date'));
            $purchaseOrder->actual_dispatch_date = $this->getDate($request->input('actual_dispatch_date'));
            $purchaseOrder->actual_delivery_date = $this->getDate($request->input('actual_delivery_date'));
            $purchaseOrder->payment_id = $request->input('payment.id');

            $invoice = $request->input('invoice');
            if (isset($invoice) && $invoice !== null) {
                $purchaseOrder->invoice_id = $request->input('invoice.id');
                $purchaseOrder->invoice_number = $request->input('invoice.invoice_number');
                $purchaseOrder->invoice_state = PurchaseOrder::NO_ENTREGADA;
            } else {
                $purchaseOrder->invoice_state = PurchaseOrder::NO_SUBIDA;
            }

            $purchaseOrder->contact_number = $request->input('contact_number');

            $purchaseOrder->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getDate($dateTxt)
    {
        if (trim($dateTxt) !== '') {
            return \Carbon\Carbon::parse($dateTxt);
        } else {
            return null;
        }
    }

    private function updateStateHistory($purchaseOrder, $request)
    {
        $idsHistory = collect();
        try {
            $history = collect($request->input('purchase_order_state_histories'));
            $history->each(function ($history) use ($purchaseOrder, &$idsHistory) {
                $purchase_order = new PurchaseOrderStateHistory();
                if (isset($history['id']) && is_numeric($history['id'])) {
                    $purchase_order = PurchaseOrderStateHistory::findOrFail($history['id']);
                }
                $purchase_order->purchase_order_id = $purchaseOrder->id;
                $purchase_order->state_order_id = $history['state_order']['id'];
                $purchase_order->description = $history['description'];
                if (trim($history['estimated_date']) !== '') {
                    $purchase_order->estimated_date = \Carbon\Carbon::parse($history['estimated_date']);
                } else {
                    $purchase_order->estimated_date = null;
                }
                $purchase_order->save();
                $idsHistory->add($purchase_order->id);
            });
            PurchaseOrderStateHistory::where('purchase_order_id', $purchaseOrder->id)->whereNotIn('id', $idsHistory->toArray())->delete();

            $currentStatus = PurchaseOrderStateHistory::selectRaw('id, if (estimated_date is null, now(), estimated_date) as total')->where('purchase_order_id', $purchaseOrder->id)->orderBy('total', 'DESC')->orderBy('id', 'DESC')->first();
            $purchaseOrder->current_status_id = $currentStatus->id;
            $purchaseOrder->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function updateOrderDetails($purchaseOrder, $request)
    {
        $idsDetails = collect();
        try {
            $details = collect($request->input('order_details'));
            $details->each(function ($detail) use ($purchaseOrder, &$idsDetails) {
                $newDetail = new OrderDetail();

                if (isset($detail['id']) && is_numeric($detail['id'])) {
                    $newDetail = OrderDetail::find($detail['id']);
                }

                $newDetail->purchase_order_id = $purchaseOrder->id;
                $newDetail->manufacturer = $detail['manufacturer'];

                $newDetail->product_id = $detail['product']['id'];
                $newDetail->internal_product_code = $detail['product']['code'];

                $newDetail->client_product_code = $detail['client_product_code'];
                $newDetail->customer_product_description = $detail['customer_product_description'];

                $newDetail->application = $detail['application'];

                $newDetail->blueprint_number = $detail['blueprint_number'];
                $newDetail->blueprint_file = $detail['blueprint_file'];
                // $newDetail->internal_quote_number = $detail['internal_quote_number'];
                $newDetail->house_quote_number = $detail['house_quote_number'];

                $newDetail->currency_id = $detail['currency']['id'];
                $newDetail->value = $detail['value'];
                $newDetail->quantity = $detail['quantity'];
                $newDetail->total_value = $detail['total_value'];

                $newDetail->save();
                $idsDetails->add($newDetail->id);
            });
            OrderDetail::where('purchase_order_id', $purchaseOrder->id)->whereNotIn('id', $idsDetails->toArray())->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function uploadFileBlueprint(Request $request)
    {

        $uuid = $request->input('id');
        $archive = $request->file('archive');
        $archiveExtension = $archive->getClientOriginalExtension();

        $ramdon = Str::random(10);
        $nameArchive = $ramdon . '-' . strtolower($archive->getClientOriginalName());
        $path = Storage::disk('public')->put('/blueprints/' . $nameArchive, file_get_contents($archive));
        $urlFinal = '/storage/blueprints/' . $nameArchive;

        return response()->json(['data' => $urlFinal, 'uuid' => $uuid, 'extension' => $archiveExtension]);
    }

    public function removeFileBlueprint(Request $request)
    {
        $pathArchive = $request->get('archive');
        $partes_ruta = pathinfo($pathArchive);
        Storage::delete('blueprints/' . $partes_ruta['basename']);

        return response()->json([], 204);
    }

    public function importPurchaseOrders(Request $request)
    {

        $file = $request->file('archive');
        $sheets = (new FastExcel)->importSheets($file);

        $encabezado = collect();
        $detalle = collect();
        $total = 0;
        // dd($sheets->get(0));

        collect($sheets->get(0))->each(function ($row) use (&$encabezado, &$total) {
            $total++;
            try {
                DB::beginTransaction();

                $customer = Customer::whereHas('user', function ($query) use ($row) {
                    return $query->query($row["cliente"]);
                })->orWhere('id', $row['cliente'])->first();

                $employee = Employee::whereHas('user', function ($query) use ($row) {
                    return $query->query($row["Vendedor"]);
                })->orWhere('id', $row['Vendedor'])->first();

                $invoice = Invoice::where('invoice_number', $row["Numero de factura"])->first();

                if (!$customer) {
                    throw new \Exception("Cliente no encontrado", "-1");
                }

                if (!$employee) {
                    throw new \Exception("Vendedor no encontrado ", "-1");
                }

                $purchaseOrder = new PurchaseOrder();
                $purchaseOrder->internal_order_number = $row["Numero de pedido interno"];
                $purchaseOrder->customer_order_number = $row["Numero de pedido Cliente"];
                $purchaseOrder->customer_id = $customer->id;
                $purchaseOrder->final_user = $row["usuario final"];
                $purchaseOrder->order_type_id = $row["Tipo de orden"];
                $purchaseOrder->zone_id = $row["Zona"];
                $purchaseOrder->seller_id = $employee->id;
                $purchaseOrder->house = $row["Casa"];
                $purchaseOrder->description = $row["Descripcion"];
                $purchaseOrder->currency_id = $row["Tipo de Moneda"];
                $purchaseOrder->total_value = $row["Valor Total"];
                $purchaseOrder->internal_quote_number = $row["numero de cotizacion interna"];
                $purchaseOrder->manufacturer_house_quotation_number = $row["número de cotización de la casa del fabricante"];
                $purchaseOrder->dispatch_guide_number = $row["numero de guia"];
                $purchaseOrder->conveyor_id = $row["Transportista"];
                $purchaseOrder->order_receipt_date = $row["fecha de recepción del pedido"];
                $purchaseOrder->offer_delivery_date = $row["fecha de entrega ofertada"];
                $purchaseOrder->delivery_date_required_customer = $row["fecha de entrega requerida cliente"];
                $purchaseOrder->expected_dispatch_date = $row["fecha de envío prevista"];
                $purchaseOrder->actual_dispatch_date = $row["fecha de envío real"];
                $purchaseOrder->actual_delivery_date = $row["fecha de entrega real"];
                $purchaseOrder->payment_id = $row["Tipo Pago"];
                $purchaseOrder->total_delivery = $row["Envio Total"];
                $purchaseOrder->trm = $row["trm"];
                $purchaseOrder->contact_number = $row["Numero de contacto"];

                $purchaseOrder->invoice_state = $invoice ? PurchaseOrder::NO_ENTREGADA : PurchaseOrder::NO_SUBIDA;
                $purchaseOrder->invoice_id = $invoice ? $invoice->id : null;
                $purchaseOrder->invoice_number = $invoice ? $invoice->invoice_number : '';

                $purchaseOrder->save();

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $row['error'] = $this->parseException($exception, $row);
                $encabezado->add($row);
            }
        });

        $details = collect($sheets->get(1));

        $details->groupBy('Numero de pedido interno')->each(function ($details, $numeroPedido) use (&$detalle, &$total) {
            $purchaseOrder = PurchaseOrder::select('id')->where('internal_order_number', $numeroPedido)->first();
            if ($purchaseOrder) {
                $details->each(function ($detail) use ($purchaseOrder, &$detalle, &$total) {

                    $total++;
                    DB::beginTransaction();
                    try {
                        $product = Product::where('code', $detail['Código producto interno'])->first();

                        if (!$product) {
                            throw new \Exception("Producto no encontrado (Numero de pedido interno) ", "-1");
                        }

                        $newDetail = new OrderDetail;

                        $newDetail->purchase_order_id = $purchaseOrder->id;
                        $newDetail->manufacturer = $detail['Casa'];

                        $newDetail->product_id = $product->id;
                        $newDetail->internal_product_code = $product->code;

                        $newDetail->client_product_code = $detail['Código de producto del cliente'];
                        $newDetail->customer_product_description = $detail['Descripción del producto del cliente'];
                        $newDetail->application = $detail['Aplicacion'];
                        $newDetail->blueprint_number = $detail['Numero de plano'];
                        $newDetail->currency_id = $detail['Tipo Moneda'];
                        $newDetail->value = $detail['Valor'];
                        $newDetail->quantity = $detail['Cantidad'];
                        $newDetail->total_value = $detail['Valor total'];
                        $newDetail->house_quote_number = $detail['Número Cotización de Casa'];
                        $newDetail->save();

                        print_r($newDetail->getAttributes());

                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        $detail['error'] = $this->parseException($exception, $detail);
                        $detalle->add($detail);
                    }
                });
            } else {
                $details->each(function ($detail) use ($purchaseOrder, &$detalle, &$total) {
                    $total++;
                    $detail['error'] = "No se encuentra el codigo de producto";
                    $detalle->add($detail);
                });
            }
        });

        $errors = $encabezado->count() + $detalle->count();
        $success = $total - $errors;

        $errors = $encabezado->count() + $detalle->count();
        $success = $total - $errors;
        if ($errors < 1 && $errors !== $total) {
            return back()->with('status', "Transacción realizada existosamente");
        } else {
            if ($success > 0) {
                return back()
                    ->with('error', $errors . " datos no se importaron correctamente")
                    ->with('status', $success . " datos se importaron correctamente")
                    ->with('lines', json_encode([
                        'encabezado' => $encabezado,
                        'detalle' => $detalle
                    ]));
            } else {
                return back()
                    ->with('error', "Ningún dato se ha importado correctamente")
                    ->with('lines', json_encode([
                        'encabezado' => $encabezado,
                        'detalle' => $detalle
                    ]));
            }
        }
    }

    public function checkPurchaseInternalOrder(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'internal_order_number' => 'unique:purchase_orders,internal_order_number'
        ], [
            'internal_order_number.unique' => "El número de pedido interno ya ha sido registrado"
        ])->errors();

        return response()->json($validation);
    }
}
