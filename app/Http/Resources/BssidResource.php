<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BssidResource extends JsonResource
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
        if ($request->has('location')) {
            $room = $this->room;
            if($room)
            {
                //$room->building;
                $room->building;
                $return['room'] = $room;
                $address = $room->building->getAddress();
                $room->building->unsetRelation('address'); 
                $return['room']->building->address =  $address;
                $room->building->site->unsetRelation('address');
            }
        }
        return $return;
    }
}
