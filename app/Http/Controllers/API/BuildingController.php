<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Building as Model;
use Illuminate\Http\Request;
use App\Http\Resources\BuildingResource as Resource;
use App\Http\Resources\BuildingCollection as ResourceCollection;
use App\Queries\BuildingQuery as Query;

class BuildingController extends Controller
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
     * @param  id  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $object = Model::find($id);
        $collection = Query::apply($request,$object);
        return new Resource($collection->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  id  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  id  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
