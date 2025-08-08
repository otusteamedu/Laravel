<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ApartmentDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="ApartmentDetails",
 *     description="API Endpoints для работы с деталями квартир"
 * )
 */
class ApartmentDetailController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/apartment-details",
     *     summary="Получить список деталей квартир",
     *     tags={"ApartmentDetails"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ApartmentDetail")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ApartmentDetail::all();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/apartment-details",
     *     summary="Создать новую деталь квартиры",
     *     tags={"ApartmentDetails"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApartmentDetailCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Создано успешно",
     *         @OA\JsonContent(ref="#/components/schemas/ApartmentDetail")
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'registred_qt' => 'required|integer',
            'lived_qt' => 'required|integer',
            'total_area' => 'required|numeric',
            'personal_account' => 'required|string',
            'account_number' => 'required|string',
            'apartment_id' => 'required|integer|exists:apartments,id',
            'tariff_id' => 'required|integer|exists:tariffs,id',
        ]);

        $apartmentDetail = ApartmentDetail::create($data);

        return response()->json($apartmentDetail, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/apartment-details/{id}",
     *     summary="Получить деталь квартиры по ID",
     *     tags={"ApartmentDetails"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID детали квартиры",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/ApartmentDetail")
     *     ),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show($id)
    {
        $apartmentDetail = ApartmentDetail::findOrFail($id);
        return $apartmentDetail;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/apartment-details/{id}",
     *     summary="Обновить деталь квартиры",
     *     tags={"ApartmentDetails"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID детали квартиры",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApartmentDetailUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Обновлено успешно",
     *         @OA\JsonContent(ref="#/components/schemas/ApartmentDetail")
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function update(Request $request, $id)
    {
        $apartmentDetail = ApartmentDetail::findOrFail($id);

        $data = $request->validate([
            'registred_qt' => 'sometimes|integer',
            'lived_qt' => 'sometimes|integer',
            'total_area' => 'sometimes|numeric',
            'personal_account' => 'sometimes|string',
            'account_number' => 'sometimes|string',
            'apartment_id' => 'sometimes|integer|exists:apartments,id',
            'tariff_id' => 'sometimes|integer|exists:tariffs,id',
        ]);

        $apartmentDetail->update($data);

        return response()->json($apartmentDetail, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/apartment-details/{id}",
     *     summary="Удалить деталь квартиры",
     *     tags={"ApartmentDetails"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID детали квартиры",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Удалено успешно"),
     *     @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function destroy($id)
    {
        $apartmentDetail = ApartmentDetail::findOrFail($id);
        $apartmentDetail->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
