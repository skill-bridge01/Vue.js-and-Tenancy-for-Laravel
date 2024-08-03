<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'invoices';

    protected $casts = [
      'deleted_at' => 'datetime',
    ];

    public function services()
    {
      return  $this->belongsToMany(Service_piece::class,'service_piece_invoices','invoice_id','service_piece_id')->withPivot(["number_of_piece","total_price","dtl"]);
    }


    public function customr()
    {
      return  $this->belongsTo(Customer::class,'customer_id');
    }

   
}
