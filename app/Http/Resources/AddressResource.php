<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            $building = $this->building;
            //print "BLDG!";
            if($building)
            {
                $building->address;
                $building->site;
                $return['building'] = $building;
                $address = $building->getAddress();
                $building->unsetRelation('address'); 
                $return['building']->address =  $address;
                $building->site->unsetRelation('address');
            }
        }
        return $return;
    }
}
