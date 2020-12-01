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

        if($request->has('location'))
        {
            $site = $this->site;
            if($site)
            {
                $site['address'] = $this->site->address;
            }
            $return['site'] = $site;
        }

        return $return;
    }
}
