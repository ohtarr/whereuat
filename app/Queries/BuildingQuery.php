<?php

namespace App\Queries;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Building;

class BuildingQuery
{
    public static function apply(Request $request, $object = null)
    {
		$query = QueryBuilder::for(Building::class)
            ->allowedFilters([
                'name',
                'description',
                'latitude',
                'longitude',
                AllowedFilter::exact('site_id'),
                AllowedFilter::exact('default_room_id'),
                AllowedFilter::exact('contact_id'),
            ])
		    ->allowedIncludes([
                'site',
                'address',
                'contact',
                'rooms',
                'defaultRoom',
            ])
            ->allowedSorts([
                'id',
                'name',
                'description',
                'latitude',
                'longitude',
            ])
            ->defaultSort('id');

            if($object)
            {
                $query->where('id',$object->id);
            }

        $collection = $query->get();

        return $collection;
    }
}