<?php

namespace App\EndPoints;

use App\Actions\Api;
use App\Actions\GenerateQrCode;
use App\Models\Invoice;
use App\Models\Piece;
use App\Models\Service;
use App\Models\Service_piece;
use App\Models\Service_piece_invoices;
use App\Transformers\InvoiceTransformer;
use Illuminate\Support\Facades\Route;
use Throwable;
use PDF;
use LaravelDaily\Invoices\Invoice as LdInvoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Illuminate\Support\Facades\Response;
class GetInvoiceFile extends Api
{
    protected $invocieid ;
    // public static function routes($router)
    // {
    //     $router->post('api/get-invoices-file',static::class);
    // }

    public function rules()
    {
        return [
            'invoice_id'=>['required'],
        ];
    }

    public function handle($invoiceinfo=null)
    {
        try{
        $invoiceinfo=$invoiceinfo?:request();
        $this->invocieid=$invoiceinfo->invoice_id;
        $service_piece_invoice_data=Service_piece_invoices::where('invoice_id',$this->invocieid)->get();
        $service_piece_data=Service_piece::where('id',$service_piece_invoice_data[0]->service_piece_id)->get();
        $service_data=Service::where('id',$service_piece_data[0]->service_id)->get();
        $piece_data=Piece::where('id',$service_piece_data[0]->piece_id)->get();
        $invoice_data=Invoice::where('id',$this->invocieid)->first();
        // dd($invoice_data);
        $service_piece_info_data=array("service_piece"=>$service_piece_data,'serviceinfo'=>$service_data,'customerinfo'=>$invoice_data);
        $piece_data[0]->invoiceservicelist=$service_piece_invoice_data;
        $piece_data[0]->invoiceData = $invoice_data;
        // --- origin script --- //
        //     $invoices =Piece::whereHas('invoiceservicelist.invoice',function($query){
        //         return $query->where('user_id',Auth()->user()->id);
        //     })->whereHas('invoiceservicelist',function($query){
        //         return $query->where('invoice_id',$this->invocieid);
        //     })->with(['invoiceservicelist'=>function($query){
        //     return $query->where('invoice_id',$this->invocieid);
        // },'invoiceservicelist.service_piece.serviceinfo'])->get();
        return $piece_data ;
        }
        catch(Throwable $ex)
    {
        env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    
    }

    }

 
    public function jsonResponse($invoice)
{
   
    if(isset(request()->pdf) and 1==1)
    {
        $html = response()->view('invoice', ['invoice'=>$invoice])->getContent();
        // $qrcode=GenerateQrCode::run(url('invocies'));
        $qrcode=GenerateQrCode::run(request()->baseURL.'/invoice/'.$invoice[0]->invoiceservicelist[0]->invoice_id);

        $nwehtml = str_replace("</body>", '<div style="text-align:center; margin:0px auto;">'.$qrcode.'</div>'."</body>", $html);
        // PDF::setOption(['dpi' => 150, 'defaultFont' => 'ArslanWessamA.ttf']);
        $pdf =PDF::loadHTML($nwehtml);
        $headers = [
            'Content-Type' => 'application/pdf', // Set the Content-Type header
            'Content-Disposition' => 'attachment; filename="invoice.pdf"', // Set the Content-Disposition header
           // 'Access-Control-Allow-Origin' => 'http://localhost:3000', // Set the Access-Control-Allow-Origin header
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS', // Set other CORS headers if needed
            'Access-Control-Allow-Headers' => 'Authorization, Content-Type, X-Requested-With, X-XSRF-TOKEN',
        ];
        
        return Response::make($pdf->output(), 200, $headers);

    }else{

        return new InvoiceTransformer($invoice);
    }
 
   
 
}

}
