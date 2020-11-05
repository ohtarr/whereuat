<?php

namespace App\Http\Controllers;

use App\Dhcp;
use Illuminate\Http\Request;

class DhcpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Dhcp::all();
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
