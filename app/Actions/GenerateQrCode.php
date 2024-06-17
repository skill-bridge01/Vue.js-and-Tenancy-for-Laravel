<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Response;
use Throwable;

class GenerateQrCode 
{
    use AsAction;


    public function handle($url)
    {
        try {
        $qrCode = QrCode::size(100)->generate($url);

        return  preg_replace('/<\?xml.*?\?>/', '', $qrCode);
        ;
    }  catch(Throwable $ex){

        return $e->getMessage();

    }
}
}
