<?php

namespace App\Http\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;

class PersonalCabinetController extends Controller
{
    /**
     * Получить данные пользователя
     *
     * @param Authenticatable $user Авторизованный пользователь
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Authenticatable $user)
    {
        $user = $user->load('profile');

        return response()->json([
            'user' => $user,
            'profile' => $user->profile,
        ]);
    }

    /**
     * Обновить профиль пользователя
     *
     * @param Request $request
     * @param User $user Авторизованный пользователь
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Authenticatable $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'about' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Обновляем основные данные пользователя
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        $user->save();

        // Обновляем или создаем профиль
        $profileData = $request->only(['phone', 'address', 'birth_date', 'about']);

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            UserProfile::create($profileData);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->load('profile'),
        ]);
    }

    /**
     * Удалить аккаунт пользователя
     *
     * @param User $user Авторизованный пользователь
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        // Получаем аутентифицированного пользователя
        $user = auth('jwt')->user();

        if ($user) {
            $user->delete();
        }

        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }
}
