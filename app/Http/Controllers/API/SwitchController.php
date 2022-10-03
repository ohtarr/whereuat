<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DeviceSwitch as Model;
use App\Http\Resources\SwitchResource as Resource;
use App\Http\Resources\SwitchCollection as ResourceCollection;
use App\Queries\SwitchQuery as Query;

class SwitchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
         //Apply proper queries and retrieve a Collection object.
         $collection = Query::apply($request);
         //Paginate the collection and include all pertinent links.
         $paginator = $collection->paginate($request->paginate ?: env('DEFAULT_PAGINATION'), 'page', $request->page)
             ->appends(request()->query());
         //Save the Collection to a tmp variable
         $tmp = $paginator->getCollection();
         //Create a new ResourceCollection object.
         $resource = new ResourceCollection($paginator);
         //Overwrite the resource collection so that it is proper type of Collection Type;
         $resource->collection = $tmp;
         return $resource;
    }

    /**
     * Display the specified resource.
     *
     * @param  id  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $object = Model::all()->find($id);
        if($object)
        {
            return new Resource($object);
        }
    }

}
