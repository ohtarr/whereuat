<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Dhcp;
use Illuminate\Http\Request;
use App\Http\Resources\DhcpCollection;

class DhcpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return Dhcp::all()->paginate(100);
        if($request->paginate)
        {
            $paginate = $request->paginate;
        } else {
            $paginate = 100;
        }
        //$scopes = collect(Dhcp::all()->paginate($paginate, 'page', $request->page));
        $scopes = Dhcp::all()->paginate($paginate, 'page', $request->page);
        //return new DhcpCollection(collect(Dhcp::all()->paginate(100, 'page', $request->page)));
        return new DhcpCollection($scopes);

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
