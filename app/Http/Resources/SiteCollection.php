<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SiteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //print_r($collection);
        if($request->has('servicenowlocation'))
        {
            $this->collection->withServiceNowLocations();
        }

        if($request->has('scopes'))
        {
            if(strtolower($request->scopes) == "full")
            {
                $this->collection->withDhcpScopesFull();
            } else {
                $this->collection->withDhcpScopes();
            }
        }

        if($request->has('rooms'))
        {
            $this->collection->withAllRooms();
        }

        if($request->has('address'))
        {
            $this->collection->withAddress();
        }

        return[
            'data'  =>  $this->collection,
        ];
    }
}
