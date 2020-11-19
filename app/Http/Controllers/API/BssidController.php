<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Bssid;
use App\Http\Resources\BssidResource;

class BssidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $allbssids = Bssid::all();

        $returnbssids = $allbssids;

        if($request->paginate)
        {
            $paginate = $request->paginate;
        } else {
            $paginate = env("DEFAULT_PAGINATION");
        }

        if($request->has('filter.name'))
        {
            $name = $request->filter['name'];
            $returnbssids = $returnbssids->filter(function ($item) use ($name) {
                return false !== stripos(strtolower($item->name), strtolower($name));
            });
        }

        if($request->has('filter.bssid'))
        {
            $requestbssid = Bssid::formatMac($request->filter['bssid']);
            $returnbssids = $returnbssids->filter(function ($item) use ($requestbssid) {
                return false !== stripos(Bssid::formatMac($item->bssid), $requestbssid);
            });

            //$returnbssids = $allbssids->where('bssid',$request->filter['bssid']);
        }

        if($request->has('filter.neighbor'))
        {
            $neighborname = $request->filter['neighbor'];
            $returnbssids = $returnbssids->filter(function ($item) use ($neighborname) {
                return false !== stripos(strtolower($item->neighbor), strtolower($neighborname));
            });
        }

        //print_r($request->all());


        //return $bssids->paginate($paginate, 'page', $request->page);
        return BssidResource::collection($returnbssids)->paginate($paginate, 'page', $request->page);
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
