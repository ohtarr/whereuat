<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DhcpCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!$request->has('options'))
        {
            $this->collection->withoutOptions();
        }

        if(!$request->has('reservations'))
        {
            $this->collection->withoutReservations();
        }

        if(!$request->has('failover'))
        {
            $this->collection->withoutFailover();
        }

        if($request->has('location'))
        {
            $this->collection->withSites();
        }

        return [
            'data'  =>  $this->collection,
        ];
    }
}
