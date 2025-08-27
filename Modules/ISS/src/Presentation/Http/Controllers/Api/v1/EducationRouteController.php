<?php

namespace ISS\App\Presentation\Http\Controllers\Api\v1;

use ISS\App\Presentation\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use ISS\App\Infrastructure\Models\EducationRoute;
use ISS\App\Presentation\Http\Resources\EducationRouteResource;
use ISS\App\Presentation\Http\Resources\EducationRouteResourceCollection;

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
        return EducationRoute::create($request->all());
    }

    /**
     * Display the specified resource.
     * маршрут GET iss/api/v1/issEducationRoute/{issEducationRoute}
     */
    public function show(string $id)
    {
        try {
            $route = EducationRoute::where('id', $id)->first();
        } catch (\Error | \Exception $e) {
            $route = null;
        }

        if (is_null($route)) {
            return [];
        } else {
            return new EducationRouteResource($route);
        }
    }

    /**
     * Update the specified resource in storage.
     * маршрут PUT iss/api/v1/issEducationRoute/{issEducationRoute}
     */
    public function update(Request $request, string $id)
    {
        $error = null;
        try {
            $updated = EducationRoute::where('id', $id)->first()->update($request->all());
        } catch (\Error | \Exception $e) {
            $updated = false;
            $error = $e->getMessage();
        }

        if ($updated) {
            return ['ok', 200];
        } else {
            return ['error' => $error, 500];
        }
    }

    /**
     * Remove the specified resource from storage.
     * маршрут DELETE iss/api/v1/issEducationRoute/{issEducationRoute}
     */
    public function destroy(string $id)
    {
        $error = null;
        try {
            $result = EducationRoute::where('id', $id)->forceDelete();
        } catch (\Error | \Exception $e) {
            $result = false;
            $error = $e->getMessage();
        }

        if ($result !== false) {
            if ($result > 0) {
                return ['ok', 200];
            } else {
                return ['message' => 'not deleted', 200];
            }
        } else {
            return ['error' => $error, 500];
        }
    }
}
