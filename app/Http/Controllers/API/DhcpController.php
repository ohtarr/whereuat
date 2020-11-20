<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Dhcp;
use Illuminate\Http\Request;
use App\Http\Resources\DhcpResource;

class DhcpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $alldhcp = Dhcp::all();

        $returndhcp = $alldhcp;

        if($request->paginate)
        {
            $paginate = $request->paginate;
        } else {
            $paginate = env("DEFAULT_PAGINATION");
        }

        if($request->has('filter.name'))
        {
            $name = $request->filter['name'];
            $returndhcp = $returndhcp->filter(function ($item) use ($name) {
                return false !== stripos(strtolower($item->name), strtolower($name));
            });
        }

        if($request->has('filter.description'))
        {
            $description = $request->filter['description'];
            $returndhcp = $returndhcp->filter(function ($item) use ($description) {
                return false !== stripos(strtolower($item->description), strtolower($description));
            });
        }

        if($request->has('filter.scopeid'))
        {
            $scopeid = $request->filter['scopeid'];
            $returndhcp = $returndhcp->filter(function ($item) use ($scopeid) {
                return false !== stripos(strtolower($item->scopeID), strtolower($scopeid));
            });
        }

        //$scopes = Dhcp::all()->paginate($paginate, 'page', $request->page);
        //return new DhcpCollection($scopes);
        return DhcpResource::collection($returndhcp)->paginate($paginate, 'page', $request->page);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($scopeID)
    {
        return Dhcp::all()->where('scopeID',$scopeID)->first();
    }

    public function indexWithSites()
    {
        //return Dhcp::allWithSites();
        return response()->file(storage_path('app/public/Scopes.json'),['Content-Type','application/json']);
        //return file(storage_path('app/public/Scopes.json'));
    }

    public function findSiteByIp($ip)
    {
        $scope = Dhcp::findScope($ip);
        if($scope)
        {
            return $scope->withSite();
        }
    }
}
