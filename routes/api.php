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
 *      path="/api/dhcp",
 *      operationId="index",
 *      tags={"DHCP"},
 *      summary="Get All DHCP Scopes",
 *      description="Return all DHCP Scopes.",
 * @OA\Parameter(
 *         name="filter[ip]",
 *         in="query",
 *         description="IP address in scope",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[name]",
 *         in="query",
 *         description="name of DHCP Scope",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[description]",
 *         in="query",
 *         description="description of DHCP Scope",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[scopeid]",
 *         in="query",
 *         description="scopeid of DHCP Scope",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
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
 * Returns All Bssids
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
 * Returns All Sites
 */

/**
* @OA\Get(
*      path="/api/site/{id}",
*      operationId="show",
*      tags={"SITE"},
*      summary="Get Site info",
*      description="Returns Site information",
*      @OA\Parameter(
*          name="id",
*          description="Site ID",
*          required=true,
*          in="path",
*          @OA\Schema(
*              type="integer"
*          )
*      ),
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
* Returns Site information.
*/

Route::apiResource('site', API\SiteController::class);

/**
 * @OA\Get(
 *      path="/api/building",
 *      operationId="index",
 *      tags={"BUILDING"},
 *      summary="Get All Buildings",
 *      description="Return all Buildings.",
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
 * Returns All Buildings
 */

/**
* @OA\Get(
*      path="/api/building/{id}",
*      operationId="show",
*      tags={"BUILDING"},
*      summary="Get Building info",
*      description="Returns Building information",
*      @OA\Parameter(
*          name="id",
*          description="Building ID",
*          required=true,
*          in="path",
*          @OA\Schema(
*              type="integer"
*          )
*      ),
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
* Returns Building information.
*/
Route::apiResource('building', API\BuildingController::class);

/**
 * @OA\Get(
 *      path="/api/room",
 *      operationId="index",
 *      tags={"ROOM"},
 *      summary="Get All Rooms",
 *      description="Return all Rooms.",
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
 * @OA\Parameter(
 *         name="filter[name]",
 *         in="query",
 *         description="name of BSSID AP",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[bssid]",
 *         in="query",
 *         description="BSSID MAC",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="filter[neighbor]",
 *         in="query",
 *         description="name of BSSID AP Neighbor (switch)",
 *         required=false,
 *         @OA\Schema(
 *           type="string"
 *         ),
 *     ),
 * @OA\Parameter(
 *         name="location",
 *         in="query",
 *         description="Include Location information with Bssid",
 *         required=false,
 *         allowEmptyValue=true,
 *         @OA\Schema(
 *           type="boolean",
 *           enum=""
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
 * Returns All Bssids
 */


Route::apiResource('bssid', API\BssidController::class);