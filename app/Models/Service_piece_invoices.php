<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_piece_invoices extends Model
{
    use HasFactory;
    
    protected $table = 'service_piece_invoices';
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


    public function service_piece()
    {
        return $this->belongsTo(Service_piece::class);
    }

    public function service_pieces()
    {
        return $this->belongsTo(Piece::class);
    }
  


    
    
}
