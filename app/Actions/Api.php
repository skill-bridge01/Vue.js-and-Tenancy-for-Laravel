<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Lorisleiva\Actions\ActionRequest;
class Api
{
    use AsAction, WithAttributes;
 
    public function getControllerMiddleware()
    {
        return ['api','auth:sanctum'];
    }

    public function asController(ActionRequest $request)
    {
        $this->fillFromRequest($request);
        $this->validateAttributes();
        return $this->handle();
    }

}