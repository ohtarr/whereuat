<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * @OA\Info(
 *      version="0.0.1",
 *      title="Where U At",
 *      description="Location Services",
 * )
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * @OA\Get(
 *     path="/api/dhcp",
 *     operationId="index",
 *     tags={"DHCP"},
 *     summary="Get All DHCP Scopes",
 *     description="Return all DHCP Scopes.",
 *     @OA\Parameter(
 *         name="filter[ip]",
 *         in="query",
 *         description="IP address in scope",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[name]",
 *         in="query",
 *         description="name of DHCP Scope",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[description]",
 *         in="query",
 *         description="description of DHCP Scope",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[scopeid]",
 *         in="query",
 *         description="scopeid of DHCP Scope",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="reservations",
 *         in="query",
 *         description="Include reservations for DHCP Scope",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *             type="boolean"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="failover",
 *         in="query",
 *         description="Include failover information for DHCP Scope",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *             type="boolean"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="location",
 *         in="query",
 *         description="Include location information for DHCP Scope",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *             type="boolean"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="paginate",
 *         in="query",
 *         description="number of records per page",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="pagination page to view",
 *         required=false,
 *         @OA\Schema(
 *           type="integer"
 *         ),
 *     ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *       @OA\Response(response=400, description="Bad request"),
 *       security={
 *           {"api_key_security_example": {}}
 *       }
 *     )
 *
 * Returns All DHCP Scopes
 */

 Route::get('/dhcp','API\DhcpController@index');

 /**
 * @OA\Get(
 *      path="/api/dhcp/{scopeID}",
 *      operationId="show",
 *      tags={"DHCP"},
 *      summary="Get Scope info",
 *      description="Returns Scope information",
 *      @OA\Parameter(
 *          name="scopeID",
 *          description="Scope ID",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * @OA\Parameter(
 *         name="reservations",
 *         in="query",
 *         description="Include reservations for DHCP Scope",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="failover",
 *         in="query",
 *         description="Include failover information for DHCP Scope",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="location",
 *         in="query",
 *         description="Include location information for DHCP Scope",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean"
 *         ),
 *     ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *       @OA\Response(response=400, description="Bad request"),
 *       security={
 *           {"api_key_security_example": {}}
 *       }
 *     )
 *
 * Returns scope information for a scope.
 */

 Route::get('/dhcp/{scopeID}','API\DhcpController@show');

 /**
 * @OA\Get(
 *      path="/api/address",
 *      operationId="index",
 *      tags={"ADDRESS"},
 *      summary="Get All Addresses",
 *      description="Return all Addresses.",
 * @OA\Parameter(
 *         name="filter[street_number]",
 *         in="query",
 *         description="street number of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[predirectional]",
 *         in="query",
 *         description="predirectional of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[street_name]",
 *         in="query",
 *         description="street_name of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[street_suffix]",
 *         in="query",
 *         description="street_suffix of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[postdirectional]",
 *         in="query",
 *         description="postdirectional of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[secondary_unit_indicator]",
 *         in="query",
 *         description="secondary_unit_indicator of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[secondary_number]",
 *         in="query",
 *         description="secondary_number of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[city]",
 *         in="query",
 *         description="city of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[state]",
 *         in="query",
 *         description="state of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[postal_code]",
 *         in="query",
 *         description="postal_code of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[country]",
 *         in="query",
 *         description="country of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[latitude]",
 *         in="query",
 *         description="latitude of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[longitude]",
 *         in="query",
 *         description="longitude of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[teams_civic_id]",
 *         in="query",
 *         description="teams_civic_id of address",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 *      @OA\Parameter(
 *         name="location",
 *         in="query",
 *         description="Include location information with Address",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean",
 *           enum=""
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="sort",
 *         in="query",
 *         description="Sort by selected field.  Default: ID",
 *         required=false,
 *         @OA\Schema(
 *           type="string",
 *           enum={"id","city","state","postal_code","country"}
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="paginate",
 *         in="query",
 *         description="number of records per page",
 *         required=false,
 *         @OA\Schema(
 *           type="integer"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="pagination page to view",
 *         required=false,
 *         @OA\Schema(
 *           type="integer"
 *         ),
 *     ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *       @OA\Response(response=400, description="Bad request"),
 *       security={
 *           {"api_key_security_example": {}}
 *       }
 *     )
 *
 * Returns All Addresses
 */

/**
* @OA\Get(
*      path="/api/address/{id}",
*      operationId="show",
*      tags={"ADDRESS"},
*      summary="Get Address info",
*      description="Returns Address information",
*      @OA\Parameter(
*          name="id",
*          description="Address ID",
*          required=true,
*          in="path",
*          @OA\Schema(
*              type="integer"
*          )
*      ),
*      @OA\Parameter(
*         name="location",
*         in="query",
*         description="Include location information with Room",
*         required=false,
*         allowEmptyValue=true,
*         @OA\Schema(
*           type="boolean",
*           enum=""
*         ),
*     ),
*      @OA\Response(
*          response=200,
*          description="successful operation"
*       ),
*       @OA\Response(response=400, description="Bad request"),
*       security={
*           {"api_key_security_example": {}}
*       }
*     )
*
* Returns Address information.
*/

Route::apiResource('address', API\AddressController::class);

/**
 * @OA\Get(
 *      path="/api/site",
 *      operationId="index",
 *      tags={"SITE"},
 *      summary="Get All Sites",
 *      description="Return all Sites.",
 *      @OA\Parameter(
 *         name="filter[name]",
 *         in="query",
 *         description="name of site",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[default_building_id]",
 *         in="query",
 *         description="default_building_id of site",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[contact_id]",
 *         in="query",
 *         description="contact_id of site",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[loc_sys_id]",
 *         in="query",
 *         description="loc_sys_id of site",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="include",
 *         in="query",
 *         description="relationships to include (select multiple)",
 *         required=false,
 *         explode=false,
 *         @OA\Schema(
 *           type="array",
 *           @OA\Items(
 *             type="string",
 *             enum={"address","contact","buildings","buildings.rooms","defaultbuilding","defaultbuilding.rooms"}
 *           ),
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="servicenowlocation",
 *         in="query",
 *         description="Include servicenowlocation information with Site",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean",
 *           enum=""
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="rooms",
 *         in="query",
 *         description="Include all rooms for with Site",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean",
 *           enum=""
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="contact911",
 *         in="query",
 *         description="Include contact911 information with Site",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean",
 *           enum=""
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="scopes",
 *         in="query",
 *         description="Include scopes information with Site",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean",
 *           enum=""
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="sort",
 *         in="query",
 *         description="Sort by selected field.  Default: ID",
 *         required=false,
 *         @OA\Schema(
 *           type="string",
 *           enum={"id","name"}
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="paginate",
 *         in="query",
 *         description="number of records per page",
 *         required=false,
 *         @OA\Schema(
 *           type="integer"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="pagination page to view",
 *         required=false,
 *         @OA\Schema(
 *           type="integer"
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(response=400, description="Bad request"),
 *         security={
 *             {"api_key_security_example": {}}
 *         }
 *     )
 *
 *     Returns All Sites
 */

/**
* @OA\Get(
*     path="/api/site/{id}",
*     operationId="show",
*     tags={"SITE"},
*     summary="Get Site info",
*     description="Returns Site information",
*     @OA\Parameter(
*         name="id",
*         description="Site ID",
*         required=true,
*         in="path",
*         @OA\Schema(
*             type="integer"
*         )
*     ),
*     @OA\Parameter(
*         name="include",
*         in="query",
*         description="relationships to include (select multiple)",
*         required=false,
*         explode=false,
*         @OA\Schema(
*             type="array",
*             @OA\Items(
*                 type="string",
*                 enum={"address","contact","buildings","buildings.rooms","defaultbuilding","defaultbuilding.rooms"}
*             ),
*         ),
*     ),
*     @OA\Parameter(
*         name="servicenowlocation",
*         in="query",
*         description="Include servicenowlocation information with Site",
*         required=false,
*         allowEmptyValue=true,
*         @OA\Schema(
*             type="boolean",
*             enum=""
*         ),
*     ),
*     @OA\Parameter(
*         name="rooms",
*         in="query",
*         description="Include all rooms for with Site",
*         required=false,
*         allowEmptyValue=true,
*         @OA\Schema(
*             type="boolean",
*             enum=""
*         ),
*     ),
*     @OA\Parameter(
*         name="contact911",
*         in="query",
*         description="Include contact911 information with Site",
*         required=false,
*         allowEmptyValue=true,
*         @OA\Schema(
*             type="boolean",
*             enum=""
*         ),
*     ),
*     @OA\Parameter(
*         name="scopes",
*         in="query",
*         description="Include scopes information with Site",
*         required=false,
*         allowEmptyValue=true,
*         @OA\Schema(
*             type="boolean",
*             enum=""
*         ),
*     ),
*     @OA\Response(
*         response=200,
*         description="successful operation"
*     ),
*     @OA\Response(response=400, description="Bad request"),
*         security={
*             {"api_key_security_example": {}}
*         }
*     )
*
*     Returns Site information.
*/

Route::apiResource('site', API\SiteController::class);

/**
 * @OA\Get(
 *     path="/api/building",
 *     operationId="index",
 *     tags={"BUILDING"},
 *     summary="Get All Buildings",
 *     description="Return all Buildings.",
 *     @OA\Parameter(
 *         name="filter[name]",
 *         in="query",
 *         description="name of building",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[description]",
 *         in="query",
 *         description="description of building",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[latitude]",
 *         in="query",
 *         description="latitude of building",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[longitude]",
 *         in="query",
 *         description="longitude of building",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[site_id]",
 *         in="query",
 *         description="site_id of building",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[address_id]",
 *         in="query",
 *         description="address_id of building",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[contact_id]",
 *         in="query",
 *         description="contact_id of building",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="filter[default_room_id]",
 *         in="query",
 *         description="default_room_id of building",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="include",
 *         in="query",
 *         description="relationships to include (select multiple)",
 *         required=false,
 *         explode=false,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(
 *                 type="string",
 *                 enum={"site","rooms","defaultRoom"}
 *             ),
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="address",
 *         in="query",
 *         description="Include address information with Building",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *             type="boolean",
 *             enum=""
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="sort",
 *         in="query",
 *         description="Sort by selected field.  Default: ID",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             enum={"id","name","description","latitude","longitude"}
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="paginate",
 *         in="query",
 *         description="number of records per page",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="pagination page to view",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(response=400, description="Bad request"),
 *         security={
 *             {"api_key_security_example": {}}
 *         }
 *     )
 *
 *     Returns All Sites
 */

/**
* @OA\Get(
*     path="/api/building/{id}",
*     operationId="show",
*     tags={"BUILDING"},
*     summary="Get Building info",
*     description="Returns Building information",
*     @OA\Parameter(
*         name="id",
*         description="Building ID",
*         required=true,
*         in="path",
*         @OA\Schema(
*             type="integer"
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="successful operation"
*     ),
*     @OA\Response(
*         response=400,
*         description="Bad request"
*     ),
*     security={
*         {"api_key_security_example": {}}
*     }
* )
*
*     Returns Building information.
*/
Route::apiResource('building', API\BuildingController::class);

/**
 * @OA\Get(
 *     path="/api/room",
 *     operationId="index",
 *     tags={"ROOM"},
 *     summary="Get All Rooms",
 *     description="Return all Rooms.",
 *     @OA\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @OA\Response(response=400, description="Bad request"),
 *     security={
 *           {"api_key_security_example": {}}
 *       }
 *     )
 *
 * Returns All Rooms
 */

/**
* @OA\Get(
*      path="/api/room/{id}",
*      operationId="show",
*      tags={"ROOM"},
*      summary="Get Room info",
*      description="Returns Room information",
*      @OA\Parameter(
*          name="id",
*          description="Room ID",
*          required=true,
*          in="path",
*          @OA\Schema(
*              type="integer"
*          )
*      ),
*      @OA\Parameter(
*         name="location",
*         in="query",
*         description="Include location information with Room",
*         required=false,
*         allowEmptyValue=true,
*         @OA\Schema(
*           type="boolean",
*           enum=""
*         ),
*     ),
*      @OA\Response(
*          response=200,
*          description="successful operation"
*       ),
*       @OA\Response(response=400, description="Bad request"),
*       security={
*           {"api_key_security_example": {}}
*       }
*     )
*
* Returns Room information.
*/
Route::apiResource('room', API\RoomController::class);


/**
 * @OA\Get(
 *      path="/api/bssid",
 *      operationId="index",
 *      tags={"BSSID"},
 *      summary="Get All Bssids",
 *      description="Return all Bssids.",
 *      @OA\Parameter(
 *          name="filter[name]",
 *          in="query",
 *          description="name of BSSID AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="filter[bssid]",
 *          in="query",
 *          description="BSSID MAC",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="filter[neighbor]",
 *          in="query",
 *          description="name of BSSID AP Neighbor (switch)",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="location",
 *          in="query",
 *          description="Include Location information with Bssid",
 *          required=false,
 *          allowEmptyValue=true,
 *          @OA\Schema(
 *              type="boolean",
 *              enum=""
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="paginate",
 *          in="query",
 *          description="number of records per page",
 *          required=false,
 *          @OA\Schema(
 *              type="integer"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="page",
 *          in="query",
 *          description="pagination page to view",
 *          required=false,
 *          @OA\Schema(
 *              type="integer"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation"
 *      ),
 *      @OA\Response(response=400, description="Bad request"),
 *      security={
 *          {"api_key_security_example": {}}
 *      }
 * )
 *
 * Returns All Bssids
 */


Route::apiResource('bssid', API\BssidController::class);

/**
 * @OA\Get(
 *      path="/api/ap",
 *      operationId="index",
 *      tags={"AP"},
 *      summary="Get All APs",
 *      description="Return all APs.",
 *      @OA\Parameter(
 *          name="name",
 *          in="query",
 *          description="name of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="model",
 *          in="query",
 *          description="model of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="serial",
 *          in="query",
 *          description="serial of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="mac",
 *          in="query",
 *          description="mac of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="ip",
 *          in="query",
 *          description="ip of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="wlc",
 *          in="query",
 *          description="wlc of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="group",
 *          in="query",
 *          description="group of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="flags",
 *          in="query",
 *          description="flags of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="status",
 *          in="query",
 *          description="status of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="integer"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="neighbor_name",
 *          in="query",
 *          description="name of AP Neighbor",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="neighbor_ip",
 *          in="query",
 *          description="ip of AP Neighbor",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="neighbor_local_interface",
 *          in="query",
 *          description="local_interface of AP",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="neighbor_remote_interface",
 *          in="query",
 *          description="remote_interface of AP Neighbor",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="bssids_bssid",
 *          in="query",
 *          description="search for APs with a specific bssid (MAC ADDRESS)",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="bssids_essid",
 *          in="query",
 *          description="search for APs with a specific essid (SSID)",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="bssids_phy",
 *          in="query",
 *          description="search for APs with a specific radio type (a-VHT,g-HT)",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="bssids_channel",
 *          in="query",
 *          description="search for APs with a specific channel (1,144)",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="bssids_eirp",
 *          in="query",
 *          description="search for APs with a specific eirp (12.0, 15.0)",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ), 
 *      @OA\Parameter(
 *          name="neighbor",
 *          in="query",
 *          description="Include neighbor information for AP",
 *          required=false,
 *          allowEmptyValue=true,
 *          @OA\Schema(
 *              type="boolean"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="bssids",
 *          in="query",
 *          description="Include bssid information for AP",
 *          required=false,
 *          allowEmptyValue=true,
 *          @OA\Schema(
 *              type="boolean"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="location",
 *          in="query",
 *          description="Include physical location information for AP",
 *          required=false,
 *          allowEmptyValue=true,
 *          @OA\Schema(
 *              type="boolean"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="paginate",
 *          in="query",
 *          description="number of records per page",
 *          required=false,
 *          @OA\Schema(
 *              type="integer"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="page",
 *          in="query",
 *          description="pagination page to view",
 *          required=false,
 *          @OA\Schema(
 *              type="integer"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *       @OA\Response(response=400, description="Bad request"),
 *       security={
 *           {"api_key_security_example": {}}
 *       }
 * )
 *
 * Returns All Aps
 */


Route::apiResource('ap', API\ApController::class);