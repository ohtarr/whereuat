<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Site;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        if($request->paginate)
        {
            $paginate = $request->paginate;
        } else {
            $paginate = env("ASSETS_PAGINATION");
        }

        $filters = [
            'id',
            'name',
            'address_id',
            'contact_id',
            'loc_sys_id',
            'location_id',
        ];

        $includes = [
            'address',
            'contact',
            'buildings',
            'buildings.rooms',
            'defaultBuilding',
            'defaultBuilding.rooms',
        ];

		$query = QueryBuilder::for(Site::class)
		    ->allowedFilters($filters)
		    ->allowedIncludes($includes);

        $sites = $query->paginate($paginate);

        return $sites;
/*         return new AssetCollection($assets);

        return Site::all(); */
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        return $site;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        //
    }
}
