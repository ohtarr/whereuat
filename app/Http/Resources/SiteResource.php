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
        $attribs = $this->getAttributes();
        $relations = $this->getRelations();
        $return = array_merge($attribs,$relations);
        //$return = $this;

        $attributes = [
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
        }

        return $return;
    }
}
