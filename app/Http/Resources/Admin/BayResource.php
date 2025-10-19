<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BayResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
