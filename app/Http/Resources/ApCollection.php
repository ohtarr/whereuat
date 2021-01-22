<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!$request->has('neighbors'))
        {
            $this->collection->withoutNeighbors();
        }

        if(!$request->has('bssids'))
        {
            $this->collection->withoutBssids();
        }

        if($request->has('location'))
        {
            $this->collection->withLocations();
        }

        return [
            'data'  =>  $this->collection,
        ];
    }
}
