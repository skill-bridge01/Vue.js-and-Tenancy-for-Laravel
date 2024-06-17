<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsticesTransformer extends JsonResource
{
    public function toArray($request)
    {


       return parent::toArray($request);
    }

         
}


?>