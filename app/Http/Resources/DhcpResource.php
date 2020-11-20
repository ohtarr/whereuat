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
        $return = $this->getAttributes();

        if(!$request->has('reservations'))
        {
            unset($return['reservations']);
        }

        if(!$request->has('failover'))
        {
            unset($return['failover']);
        }

        if($request->has('site'))
        {
            $return['site'] = $this->site;
        }

        return $return;
    }
}
