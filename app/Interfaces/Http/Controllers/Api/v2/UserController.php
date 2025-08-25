<?php

namespace App\Interfaces\Http\Controllers\Api\v2;

use App\Interfaces\Http\Requests\Api\v2\UserController\UpdateEmailRequest;
use App\Interfaces\Http\Requests\Api\v2\UserController\UpdateNameRequest;
use App\Interfaces\Http\Requests\Api\v2\UserController\UpdatePasswordRequest;
use App\Interfaces\Response\WebResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController
{
    public function showProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $response = new WebResponse(
                true,
                [
                    "name" => $user->getName(),
                    "role" => $user->getRole(),
                    "email" => $user->getEmail(),
                    "created_at" => $user->getCreatedAt(),
                    "updated_at" => $user->getUpdatedAt(),
                ],
                'Сведения пользователя',
                [],
                200
            );
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    public function updateName(UpdateNameRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            if ($user->getPassword() === $request->get('newName')) {
                throw new Exception('Новое имя совпадает со старым');
            }
            $user->update([
                'name' => $request->get('newName'),
            ]);
            $response = new WebResponse(
                true,
                [
                    "name" => $user->getName(),
                    "role" => $user->getRole(),
                    "email" => $user->getEmail(),
                    "created_at" => $user->getCreatedAt(),
                    "updated_at" => $user->getUpdatedAt(),
                ],
                'Имя пользователя изменено',
                [],
                200
            );
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    public function updateEmail(UpdateEmailRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            if ($user->getEmail() === $request->get('newEmail')) {
                throw new Exception('Новый email совпадает со старым');
            }
            $user->update([
                'email' => $request->get('newEmail'),
            ]);
            $response = new WebResponse(
                true,
                [
                    "name" => $user->getName(),
                    "role" => $user->getRole(),
                    "email" => $user->getEmail(),
                    "created_at" => $user->getCreatedAt(),
                    "updated_at" => $user->getUpdatedAt(),
                ],
                'Email пользователя изменен',
                [],
                200
            );
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            if ($user->getPassword() === $request->get('newPassword')) {
                throw new Exception('Новый пароль совпадает со старым');
            }
            $user->update([
                'password' => Hash::make($request->get('newPassword')),
            ]);
            $response = new WebResponse(
                true,
                [
                    'password' => $user->getPassword(),
                ],
                'Пароль пользователя изменен',
                [],
                200
            );
        } catch (Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json(
                $response->toArray(),
                $response->statusCode,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'JSON_UNESCAPED_UNICODE' => true
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
