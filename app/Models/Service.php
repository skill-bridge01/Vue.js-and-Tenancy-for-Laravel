<?php

namespace App\Models;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // use HasFactory, CentralConnection;
    use HasFactory;
    public $table = 'services';
    
    protected $fillable = [
        'services_title',
        'is_checked',
        'is_shown'
    ];
    public function pieces()
    {
        return $this->belongsToMany(Piece::class,'service_piece','service_id','piece_id');
    }
}
