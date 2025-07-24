<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="News API",
 *      version="2.0",
 *      description="API для новостного портала"
 * )
 *
 * @OA\Tag(
 *     name="News",
 *     description="API для управления новостями"
 * )
 *
 * @OA\Schema(
 *     schema="News",
 *     type="object",
 *     title="News",
 *     required={"id", "title", "content"},
 *     @OA\Property(property="id", type="integer", example=123),
 *     @OA\Property(property="title", type="string", example="Заголовок новости"),
 *     @OA\Property(property="content", type="string", example="Текст новости"),
 *     @OA\Property(property="author_id", type="integer", example=1),
 *     @OA\Property(property="category_id", type="integer", example=5),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="is_draft", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *
 * @OA\Schema(
 *     schema="NewsCreateRequest",
 *     type="object",
 *     required={"title","content","author_id","category_id","is_draft"},
 *     @OA\Property(property="title", type="string", example="Новая новость"),
 *     @OA\Property(property="content", type="string", example="Текст новости"),
 *     @OA\Property(property="author_id", type="integer", example=1),
 *     @OA\Property(property="category_id", type="integer", example=2),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="is_draft", type="boolean", example=true),
 * )
 *
 * @OA\Schema(
 *     schema="NewsUpdateRequest",
 *     type="object",
 *     required={"title","content","category_id","is_draft"},
 *     @OA\Property(property="title", type="string", example="Обновлённый заголовок"),
 *     @OA\Property(property="content", type="string", example="Обновлённый текст"),
 *     @OA\Property(property="author_id", type="integer", example=1),
 *     @OA\Property(property="category_id", type="integer", example=3),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="is_draft", type="boolean", example=false),
 * )
 *
 * @OA\PathItem(
 *     path="/api/v2/news",
 *     @OA\Get(
 *         operationId="getNewsList",
 *         summary="Получить список новостей с пагинацией",
 *         tags={"News"},
 *         security={{"bearerAuth":{}}},
 *         @OA\Parameter(name="limit", in="query", description="Количество записей на странице", required=false,
 *              @OA\Schema(type="integer", default=10)
 *         ),
 *         @OA\Parameter(name="page", in="query", description="Номер страницы", required=false,
 *              @OA\Schema(type="integer", default=1)
 *         ),
 *         @OA\Response(response=200, description="Список новостей",
 *              @OA\JsonContent(
 *                  type="object",
 *                  @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/News")),
 *                  @OA\Property(property="meta", type="object",
 *                      @OA\Property(property="total", type="integer"),
 *                      @OA\Property(property="limit", type="integer"),
 *                      @OA\Property(property="offset", type="integer")
 *                  )
 *              )
 *         )
 *     ),
 *     @OA\Post(
 *         operationId="createNews",
 *         summary="Создать новость",
 *         tags={"News"},
 *         security={{"bearerAuth":{}}},
 *         @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/NewsCreateRequest")),
 *         @OA\Response(response=201, description="Новость успешно создана",
 *              @OA\JsonContent(ref="#/components/schemas/News")
 *         ),
 *         @OA\Response(response=400, description="Ошибка в данных запроса"),
 *         @OA\Response(response=500, description="Внутренняя ошибка сервера")
 *     )
 * )
 *
 * @OA\PathItem(
 *     path="/api/v2/news/{id}",
 *     @OA\Get(
 *         operationId="getNewsById",
 *         summary="Получить новость по ID",
 *         tags={"News"},
 *         security={{"bearerAuth":{}}},
 *         @OA\Parameter(name="id", in="path", description="ID новости", required=true,
 *              @OA\Schema(type="integer")
 *         ),
 *         @OA\Response(response=200, description="Данные новости", @OA\JsonContent(ref="#/components/schemas/News")),
 *         @OA\Response(response=404, description="Новость не найдена")
 *     ),
 *     @OA\Put(
 *         operationId="updateNews",
 *         summary="Обновить новость",
 *         tags={"News"},
 *         security={{"bearerAuth":{}}},
 *         @OA\Parameter(name="id", in="path", description="ID новости", required=true,
 *              @OA\Schema(type="integer")
 *         ),
 *         @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/NewsUpdateRequest")),
 *         @OA\Response(response=200, description="Новость обновлена", @OA\JsonContent(ref="#/components/schemas/News")),
 *         @OA\Response(response=400, description="Ошибка в данных запроса"),
 *         @OA\Response(response=404, description="Новость не найдена"),
 *         @OA\Response(response=500, description="Ошибка при сохранении новости")
 *     ),
 *     @OA\Delete(
 *         operationId="deleteNews",
 *         summary="Удалить новость",
 *         tags={"News"},
 *         security={{"bearerAuth":{}}},
 *         @OA\Parameter(name="id", in="path", description="ID новости", required=true,
 *             @OA\Schema(type="integer")
 *         ),
 *         @OA\Response(response=204, description="Новость удалена"),
 *         @OA\Response(response=404, description="Новость не найдена"),
 *         @OA\Response(response=500, description="Ошибка при удалении новости")
 *     )
 * )
 */
class NewsApiAnnotations
{
    // Класс нужен чтобы аннотации не игнорировались
}
