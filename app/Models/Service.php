<?php

namespace App\Models;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    // use HasFactory, CentralConnection;
    use HasFactory;
    use SoftDeletes;
    public $table = 'services';
    
    protected $fillable = [
        'services_title',
        'is_checked',
        'is_shown'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    
    public function pieces()
    {
        return $this->belongsToMany(Piece::class,'service_piece','service_id','piece_id');
    }
}
