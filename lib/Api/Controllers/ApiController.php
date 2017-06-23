<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 21.06.17
 * Time: 11:21
 */

namespace OParl\Website\Api\Controllers;

use function Swagger\scan;

/**
 * @SWG\Swagger(
 *     schemes={"https"},
 *     host="dev.oparl.org",
 *     basePath="/api",
 *     @SWG\Info(
 *         title="OParl Developer Platform API",
 *         description="Meta information concerning the OParl ecosystem",
 *         version="0"
 *     )
 * )
 */
class ApiController
{
    public function swaggerJson()
    {
        $swagger = scan(base_path('lib/Api/Controllers'));

        return response()->json(json_decode($swagger));
    }

    public function index()
    {
        return view('api.index');
    }
}