<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Dhcp;
use Illuminate\Http\Request;
use App\Http\Resources\DhcpResource;
use App\Search\DhcpSearch;

class DhcpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->paginate)
        {
            $paginate = $request->paginate;
        } else {
            $paginate = env("DEFAULT_PAGINATION");
        }

        $return = DhcpSearch::apply($request);

        return DhcpResource::collection($return)->paginate($paginate, 'page', $request->page)->appends(request()->query());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($scopeID)
    {
        //return Dhcp::all()->where('scopeID',$scopeID)->first();
        return new DhcpResource(Dhcp::all()->where('scopeID',$scopeID)->first());
    }

}
