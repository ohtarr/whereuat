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
 *      summary="Get All DHCP scopes",
 *      description="Return all DHCP scopes.",
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
 *      path="/api/ipsite/{ip}",
 *      operationId="findSiteByIp",
 *      tags={"DHCP"},
 *      summary="Get Scope info, including SITE information if available",
 *      description="Returns Scope information, including SITE information if available.",
 *      @OA\Parameter(
 *          name="ip",
 *          description="IP Address",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
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
 * Returns scope information for an IP
 */
Route::get('/ipsite/{ip}','API\DhcpController@findSiteByIp');

/**
 * @OA\Get(
 *      path="/api/scopesites",
 *      operationId="allWithSites",
 *      tags={"DHCP"},
 *      summary="Get all scopes with SITE information included.",
 *      description="Get all scopes with SITE information included.",
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
 * Returns all Scopes, including SITE/ADDRESS information.
 */
Route::get('/scopesites/','API\DhcpController@indexWithSites');

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