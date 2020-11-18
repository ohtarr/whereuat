<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Site;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\SiteResource;

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
            $paginate = env("DEFAULT_PAGINATION");
        }

		$query = QueryBuilder::for(Site::class)
            ->allowedAppends([
                'servicenowlocation',
                'rooms',
                'contact911',
            ])
            ->allowedFilters([
                'id',
                'name',
                'address_id',
                'contact_id',
                'loc_sys_id',
                'location_id',
            ])
		    ->allowedIncludes([
                'address',
                'contact',
                'buildings',
                'buildings.rooms',
                'defaultbuilding',
                'defaultbuilding.rooms',
            ])
            ->allowedSorts('name')
            ->defaultSort('id');

        //$sites = $query->paginate($paginate);
        $sites = $query->get();

        //return $sites;
        return $sites->paginate($paginate, 'page', $request->page);
        //return SiteResource::collection($sites);

        //return Site::all(); */
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
