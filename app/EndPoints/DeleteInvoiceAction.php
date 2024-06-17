<?php
namespace App\Endpoints;

use App\Actions\Api;
use App\Models\Piece;
use App\Models\Invoice;

use Throwable;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;

class DeleteInvoiceAction {
  use AsAction, WithAttributes;

  public function handle($id, $invoiceInfo = null) {

    try {
      //code...
      $invoice = Invoice::find($id);
      // ->update(['piece_title' => $data->title]);
      $invoice->delete();
      return $invoice;
     
    } catch (\Throwable $ex) {
      //throw $th;
      env("APP_DEBUG")?abort(500,$ex->getMessage()):abort(500,"something went wrong.");
    }
  }

  public function jsonResponse($data) {
    return response()->json($data);
  }
}
