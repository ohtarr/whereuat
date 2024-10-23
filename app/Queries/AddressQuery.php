<?php

namespace App\Queries;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Address;

class AddressQuery
{
    public static function apply(Request $request, $object = null)
    {
        $query = QueryBuilder::for(Address::class)
            ->allowedFilters([
                'street_number',
                'predirectional',
                'street_name',
                'street_suffix',
                'postdirectional',
                'secondary_unit_indicator',
                'secondary_number',
                'city',
                'state',
                'postal_code',
                'country',
                'latitude',
                'longitude',
                'teams_civic_id',
            ])->allowedIncludes([
                'building',
                'building.site',
                'building.rooms',
            ])
            ->allowedSorts('id','city','state','postal_code','country')
            ->defaultSort('id');

            if($object)
            {
                $query->where('id',$object->id);
            }

        $collection = $query->get();

        return $collection;
    }
}