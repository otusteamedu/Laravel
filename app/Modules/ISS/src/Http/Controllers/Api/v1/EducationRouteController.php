<?php

namespace App\Modules\ISS\src\Http\Controllers\Api\v1;

use App\Modules\ISS\src\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Http\Resources\EducationRouteResource;
use App\Modules\ISS\src\Http\Resources\EducationRouteResourceCollection;



/**
 * Ресурсный контроллер, использует ресурсные коллекции
 * для CRUD операций с сущностью EducationRoute (справочный маршрут ИОС)
 *
 * защищен Oauth (Passport)
 * для авторизации в Passport
 * входим в систему по POST api/oauth/login или регаемся по api/oauth/register
 * с передачей формы со всеми полями Users (для регистрации)
 * или только email \ password для логина
 *
 * и затем на защищенные маршруты с звголовком
 * Authorization = Bearer "token....."
 *
 * для выхода с АПИ POST api/oauth/logout c звголовком
 * Authorization = Bearer "token....."
 */

class EducationRouteController
{
    /**
     * Display a listing of the resource.
     * маршрут GET iss/api/v1/issEducationRoute
     */
    public function index()
    {
        return new EducationRouteResourceCollection(EducationRoute::all());
    }

    /**
     * Store a newly created resource in storage.
     * маршрут POST iss/api/v1/issEducationRoute
     */
    public function store(Request $request)
    {
        EducationRoute::create($request->all());
    }

    /**
     * Display the specified resource.
     * маршрут GET iss/api/v1/issEducationRoute/{issEducationRoute}
     */
    public function show(string $id)
    {
        return new EducationRouteResource(EducationRoute::where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     * маршрут PUT iss/api/v1/issEducationRoute/{issEducationRoute}
     */
    public function update(Request $request, string $id)
    {
        $issUser = EducationRoute::where('id', $id)->update($request->all());
        return new EducationRouteResource($issUser);
    }

    /**
     * Remove the specified resource from storage.
     * маршрут DELETE iss/api/v1/issEducationRoute/{issEducationRoute}
     */
    public function destroy(string $id)
    {
        EducationRoute::where('id', $id)->delete();
        return response('ok', 200);
    }
}
