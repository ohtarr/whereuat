<?php

namespace App\Queries;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Site;

class SiteQuery
{
    public static function apply(Request $request, $object = null)
    {
        $query = QueryBuilder::for(Site::class)
            ->allowedFilters([
                'name',
                AllowedFilter::exact('default_building_id'),
                AllowedFilter::exact('loc_sys_id'),
            ])
		    ->allowedIncludes([
                'buildings',
                'buildings.rooms',
                'defaultbuilding',
                'defaultbuilding.address',
                'defaultbuilding.rooms',
                'defaultbuilding.contact',
                'defaultbuilding.defaultroom',
            ])
            ->allowedSorts('id','name')
            ->defaultSort('id');

            if($object)
            {
                $query->where('id',$object->id);
            }

        $collection = $query->get();

        return $collection;
    }
}