<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DhcpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //$return = $this->getAttributes();

        if(!$request->has('options'))
        {
            $this->withoutOptions();
            //unset($return['failover']);
        }

        if(!$request->has('reservations'))
        {
            $this->withoutReservations();
            //unset($return['reservations']);
        }

        if(!$request->has('failover'))
        {
            $this->withoutFailover();
            //unset($return['failover']);
        }

        if($request->has('location'))
        {
            $this->withSite();
        }

        return $this->getAttributes();
    }
}
