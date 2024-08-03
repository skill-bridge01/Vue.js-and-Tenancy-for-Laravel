<?php

namespace App\Models;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    // use HasFactory, CentralConnection;
    use HasFactory;
    use SoftDeletes;
    public $table = 'company_data';

    protected $fillable = [
      'name','address','logo'
    ];

    protected $casts = [
      'deleted_at' => 'datetime',
    ];
}
