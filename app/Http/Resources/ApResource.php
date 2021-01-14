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
        $return = $this->getAttributes();

        if(!$request->has('neighbor'))
        {
            unset($return['neighbor']);
        }

        if(!$request->has('bssids'))
        {
            unset($return['bssids']);
        }

        if($request->has('location'))
        {
            $room = $this->room;
            if($room)
            {
                $room->building;
                $room->building->site;
                $room->building->address = $room->building->address;
                $return['room'] = $room;
            }
        }

        return $return;
    }
}
