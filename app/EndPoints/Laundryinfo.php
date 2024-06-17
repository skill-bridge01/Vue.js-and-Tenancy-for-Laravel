<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Throwable;
use PDF;
use LaravelDaily\Invoices\Invoice as LdInvoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
class Laundryinfo extends Api
{
    
    public static function routes($router)
    {
        $router->post('api/get-laundry-info',static::class);
    }

   

    public function handle()
    {
        try{
               return ['laundryname'=>'مغسلة دانة',"logo"=> env('APP_URL').Storage::url('logo.jpeg')] ;
        }
        catch(Throwable $ex)
   {
    env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
 
   }

    }

 
    public function jsonResponse($invoice)
{
        return $invoice;

}

}
