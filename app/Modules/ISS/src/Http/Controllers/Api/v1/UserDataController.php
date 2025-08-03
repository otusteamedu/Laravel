<?php

namespace App\Modules\ISS\src\Http\Controllers\Api\v1;

use App\Modules\ISS\src\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Modules\ISS\src\Http\Resources\UserDataResource;
use App\Modules\ISS\src\Http\Resources\UserDataResourceCollection;
use App\Modules\ISS\src\Models\UserData;

/**
 * Ресурсный контроллер, использует ресурсные коллекции
 * для CRUD операций с сущностью UserData (пользователь ИОС)
 *
 * защищен JWT
 * для авторизации в JWT перейти по маршруту api/jwt/login и передать форму POST (email+password)
 * получить jwt токен и поставить его в запросы в заголовок
 * Authorization = "Bearer _token string...._"
 *
 * для выхода с АПИ api/jwt/logout
 */

class UserDataController extends Controller
{
    /**
     * Display a listing of the resource.
     * маршрут GET iss/api/v1/issUser
     */
    public function index()
    {
        return new UserDataResourceCollection(UserData::all());
    }

    /**
     * Store a newly created resource in storage.
     * маршрут POST iss/api/v1/issUser
     */
    public function store(Request $request)
    {
        UserData::create($request->all());
    }

    /**
     * Display the specified resource.
     * маршрут GET iss/api/v1/issUser/{issUser}
     */
    public function show(string $id)
    {
        return new UserDataResource(UserData::where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     * маршрут PUT iss/api/v1/issUser/{issUser}
     */
    public function update(Request $request, string $id)
    {
        $issUser = UserData::where('id', $id)->update($request->all());
        return new UserDataResource($issUser);
    }

    /**
     * Remove the specified resource from storage.
     * маршрут DELETE iss/api/v1/issUser/{issUser}
     */
    public function destroy(string $id)
    {
        UserData::where('id', $id)->delete();
        return response('ok', 200);
    }
}
