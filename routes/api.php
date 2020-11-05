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

 Route::get('/dhcp','DhcpController@index');

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

 Route::get('/dhcp/{scopeID}','DhcpController@show');

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
Route::get('/ipsite/{ip}','DhcpController@findSiteByIp');

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
Route::get('/scopesites/','DhcpController@indexWithSites');
