<?php

namespace App\EndPoints;

use App\Actions\Api;
// use App\Actions\SendInvoicViaWhatsapp;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use Twilio\Rest\Client;
use App\Models\Service_piece_invoices;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

use Illuminate\Support\Facades\Route;
use Throwable;

class CreateInvoiceWeb
{
    use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/create-invoice',static::class);
    // }

    public function rules()
    {
        return [
            'customer_id' => ['exists:customers,id','required'],
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/create-invoice",
     *     tags={"Invoice"},
     *     summary="Create invoice",
     *     description="Create new invoice",
     *     @OA\Parameter(
     *         name="customer_name",
     *         in="query",
     *         description="Please enter customer name",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="discount",
     *         in="query",
     *         description="Please enter discount",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="note",
     *         in="query",
     *         description="Please enter Note",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="payment_method",
     *         in="query",
     *         description="Please enter payment method",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *          name="Service_Piece_ID",
     *           in="query",
     *           description="Please enter service piece ID(s)",
     *           required=true,
    *            @OA\Schema(
    *               type="integer"
    *            )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Invoice created successfully"
     *     ),
     *     
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     */

    public function handle($invoceinfo=null)
    {
        try{
            $invoceinfo=$invoceinfo?:request();
            
            $lead = Invoice::whereDate('created_at', date('Y-m-d'))->get()->count();
            $customer = Customer::firstOrNew(['mobile'=>data_get($invoceinfo,'mobile',null)]);
            $customer->name = data_get($invoceinfo,'customer_name',null);
            $customer->save();
            $invoice = new Invoice(); 
            $invoice->customer_name = data_get($invoceinfo,'customer_name',null); 
            $invoice->customer_id = $customer->id;
            $invoice->discount = data_get($invoceinfo,'discount',0); 
            $invoice->note = data_get($invoceinfo,'note','');
            $invoice->payment_method = data_get($invoceinfo, 'payment_method', '');
            $invoice->daily_id = $lead+1; 
            $invoice->user_id = auth()->user()->id;
            $invoice->save();
            $service_piece_ids = data_get($invoceinfo,'service_piece_ids',null); 

            foreach($service_piece_ids as $service)
            {
                $dtl = data_get($service,'dtl',null);
                $number_of_piece = data_get($service,'number_of_piece',null);
                $total_price = data_get($service,'total_price',null);
                $note = data_get($service,'note',null);
                $discount = data_get($service,'discount',null);
            
                $service_invoice = new Service_piece_invoices(); 
                $service_invoice->invoice_id = data_get($invoice,'id',null); 
                $service_invoice->service_piece_id = data_get($service,'id',null); 
                $service_invoice->number_of_piece = $number_of_piece;
                $service_invoice->total_price = $total_price;
                $service_invoice->note = $note;
                $service_invoice->discount = $discount;
                $service_invoice->dtl = $dtl;
                $service_invoice->save();

            }

            return $invoice ;
        }
        catch(Throwable $ex)
        {
            env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
        }

    }

 
    public function jsonResponse($invoice)
    {
        // SendInvoicViaWhatsapp::run();
        return ["invoice"=>$invoice,"status"=>true];
    
    }
}
