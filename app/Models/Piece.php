<?php

namespace App\Models;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Piece extends Model
{
    // use HasFactory, CentralConnection;
    use HasFactory;
    use SoftDeletes;
    public $table = 'pieces';

    protected $fillable = [
      'piece_title',
    ];

    protected $casts = [
      'deleted_at' => 'datetime',
    ];

    public function serviceslist()
    {
      return  $this->hasMany(Service_piece::class);
    }

    public function services()
    {
      return  $this->belongsToMany(Service::class,'service_piece','piece_id','service_id')->withPivot(["id","price"]);
    }


    public function invoiceservicelist()
    {
      return  $this->hasManyThrough(Service_piece_invoices::class,Service_piece::class,'piece_id','service_piece_id','id','id');
    }

    public function invoice()
    {
      return  $this->hasOneThrough(Invoice::class,Service_piece_invoices::class,'invoice_id','id');
    }

    
}
