<?php

namespace App\Queries;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Room;

class RoomQuery
{
    public static function apply(Request $request, $object = null)
    {
		$query = QueryBuilder::for(Room::class)
            ->allowedFilters([
                'name',
                'description',
                AllowedFilter::exact('building_id'),
            ])
		    ->allowedIncludes([
                'building',
                'building.address',
                'building.site'
            ])
            ->allowedSorts([
                'id',
                'name',
                'description',
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