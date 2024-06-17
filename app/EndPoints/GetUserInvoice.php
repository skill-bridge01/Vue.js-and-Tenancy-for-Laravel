<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Service_piece;
use App\Models\Service_piece_invoices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class GetUserInvoice
{
    use AsAction, WithAttributes;
    
    // public static function routes($router)
    // {
    //     $router->post('api/get-user-invoices',static::class);
    // }

    /**
     * @OA\Post(
     *      path="/api/get-user-invoices",
     *      tags={"Invoice"},
     *      summary="Get list of invoice",
     *      description="Returns list of invoice",
     *      security={
     *           {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successfully get invoice list"
     *       ),
     *       @OA\Response(response=404, description="Not Found"),
     *     )
     *
     * Returns list of invoice
     */
   

    public function handle($userinfo=null)
    {
        try{
        $userinfo=$userinfo?:request();
        $invoices=Invoice::where('user_id',Auth()->user()->id)->get();
        foreach($invoices as $invoice_item){
            $service_piece_invoice_data=Service_piece_invoices::where('invoice_id',$invoice_item->id)->get();
                $service_data=array();
            foreach($service_piece_invoice_data as $service_piece_invoice_item){
                $service_piece_data=Service_piece::where('id',$service_piece_invoice_item->service_piece_id)->first()->toArray();
                $service_info=Service::where('id',$service_piece_data['service_id'])->first()->toArray();
                array_push($service_piece_data,array('pivot'=>$service_piece_invoice_item));
                array_push($service_piece_data,array('serviceinfo'=>$service_info));
                array_push($service_data,$service_piece_data);
            }
            $service_obj_data=json_decode(json_encode($service_data));
            $invoice_item->services=$service_obj_data;
            foreach ($invoice_item->services as $item){
                $item->pivot=$item->{0}->pivot;
                $item->serviceinfo=$item->{1}->serviceinfo;
            }
            $customer_data=Customer::where('id',$invoice_item->customer_id)->first();
            
            $invoice_item->customr=$customer_data; 
        }
        // dd($invoices);
      return $invoices ;
        }
        catch(Throwable $ex)
   {
    env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
 
   }

    }

 
    public function jsonResponse($invoices)
{
   
    return ["invoices"=>$invoices,"status"=>true];
 
}
}
