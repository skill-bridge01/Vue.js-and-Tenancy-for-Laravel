<?php

namespace App\Models;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_piece extends Model
{
    // use HasFactory, CentralConnection;
    use HasFactory;

    public $table = 'service_piece';
    public function serviceinfo()
    {
        return $this->belongsTo(Service::class,"service_id","id");  
    }

    public function pieceinfo()
    {
        return $this->belongsTo(Piece::class,"piece_id","id");  
    }


    public function service_piece_invoices()
    {
      return  $this->hasMany(Service_piece_invoices::class);
    }
}
