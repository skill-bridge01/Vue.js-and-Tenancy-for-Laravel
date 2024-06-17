<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';

    public function services()
    {
      return  $this->belongsToMany(Service_piece::class,'service_piece_invoices','invoice_id','service_piece_id')->withPivot(["number_of_piece","total_price","dtl"]);
    }


    public function customr()
    {
      return  $this->belongsTo(Customer::class,'customer_id');
    }

   
}
