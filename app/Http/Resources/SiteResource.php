<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
/*         $array = explode(",", $request->include);
        print_r($array);
        foreach($array as $include)
        {
            $includesarray = explode(".", $include);
            foreach($includesarray)
            {

                $question = ${'question'.$var}[0];

            }



            if($request->has($include))
            {
                $this->
            }
        } */
        $attribs = $this->getAttributes();
        $relations = $this->getRelations();
        $return = array_merge($attribs,$relations);

/*         $attributes = [
            'address',
            'contact',
            'buildings',
            'defaultbuilding',
            'servicenowlocation',
            'rooms',
            'contact911',
            'scopes',
        ];

        foreach($attributes as $attribute)
        {
            if($request->has($attribute))
            {
                $value = $this->$attribute;
                if($value)
                {
                    $return[$attribute] = $value;
                }
            }
        } */

/*         if($request->has('address'))
        {
            $defaultBuilding = $this->defaultBuilding;
            $room = $this->getRoom();
            if($room)
            {
                $room->building;
                $room->building->site;
                $room->building->address = $room->building->getAddress();
                $room->building->site->unsetRelation('default_building');
                $return['room'] = $room;
            }
        } */
/*         $items = [
            'servicenowlocation'    =>  'getServiceNowLocation',
            'scopes'                =>  'getScopes'
        ];
        foreach($items as $key => $method)
        {
            if($request->has($key))
            {
                $value = $this->$method();

                if($value)
                {
                    $return[$key] = $value;
                }
            }
        } */

        if($request->has('servicenowlocation'))
        {
            $return['servicenowlocation'] =  $this->getServiceNowLocation();
        }

        if($request->has('scopes'))
        {
            if($request->scopes == "full")
            {
                $return['scopes'] =  $this->getScopes();
            } else {
                $return['scopes'] =  $this->getScopes()->withoutReservations()->withoutFailover()->withoutOptions();
            }
        }

        if($request->has('rooms'))
        {
            $return['rooms'] =  $this->getAllRooms();
        }

        if($request->has('address'))
        {
            $return['address'] =  $this->getAddress();
        }

        return $return;
    }
}
