<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!$request->has('neighbor'))
        {
            $this->withoutNeighbor();
        }

        if(!$request->has('bssids'))
        {
            $this->withoutBssids();
        }

        if($request->has('location'))
        {
            $this->withLocation();
        }

        return $this->getAttributes();
    }
}
