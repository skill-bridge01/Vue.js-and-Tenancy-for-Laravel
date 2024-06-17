<?php

namespace App\EndPoints;

use App\Actions\Api;
// use App\Actions\SendInvoicViaWhatsapp;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Lorisleiva\Actions\Concerns\AsAction;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Throwable;

class GenerateQRCodeAction extends Api
{
    
    public static function routes($router)
    {
        $router->post('api/qr-code',static::class);
    }

    public function handle($data=null)
    {
        try{
            $data=$data?:request();
            $qrCode = QrCode::size($data->size)->generate("laundry.test");
    
            return $qrCode;
        }
        catch(Throwable $ex)
   {
    env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
   }

    }

 
    public function jsonResponse($qr)
{
   
    return ["qr"=>$qr,"status"=>true];
 
}
}
