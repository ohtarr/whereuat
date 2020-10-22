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

    public function findSiteByIp($ip)
    {
        return Dhcp::findScope($ip)->findSite();
    }


}
