<?php

namespace App\EndPoints;

use App\Actions\Api;
// use App\Actions\SendInvoicViaWhatsapp;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Throwable;

class CreateInvoice extends Api
{
    
    // public static function routes($router)
    // {
    //     $router->post('api/app-create-invoice',static::class);
    // }

    public function rules()
    {
        return [
            'customer_name' => ['min:2','required'],
            'mobile'=>['required']
        ];
    }

    public function handle($invoceinfo=null)
    {
        try{
        $invoceinfo=$invoceinfo?:request();
        $customer=Customer::firstOrNew(['mobile'=>data_get($invoceinfo,'mobile',null)]);
        $customer->name =data_get($invoceinfo,'customer_name',null);
        $customer->save();
        $invoice = new Invoice(); 
        $invoice->customer_name= data_get($invoceinfo,'customer_name',null); 
        $invoice->customer_id= $customer->id;
        $invoice->user_id=auth()->user()->id;
        $invoice->save();
        $service_piece_ids= data_get($invoceinfo,'service_piece_ids',null); 

        foreach($service_piece_ids as $service)
        {
            $dtl=data_get($service,'dtl',null);
            $number_of_piece=data_get($service,'number_of_piece',null);
            $total_price=data_get($service,'total_price',null);

            $invoice->services()->attach( data_get($service,'id',null),['number_of_piece'=>$number_of_piece,'total_price'=>$total_price,'dtl'=>$dtl]); 
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
