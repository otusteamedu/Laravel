<?php

namespace App\Http\Controllers\Api\v3;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdatePasswordRequest;
use App\Http\Requests\Api\Profile\UpdateRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct() {}

    public function getProfile(Request $request): JsonResource
    {
        $user = $request->user();
        ProfileResource::withoutWrapping();
        return new ProfileResource($user);
    }

    public function updateProfile(UpdateRequest $request): JsonResource
    {
        $data = $request->validated();

        $user = auth()->user();
        $user->update($data);
        
        ProfileResource::withoutWrapping();
        return new ProfileResource($user);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResource
    {
        $data = $request->validated();
        $passwordHash = Hash::make($data['password']);

        $user = auth()->user();
        $user->password = $passwordHash;
        $user->save();

        ProfileResource::withoutWrapping();
        return new ProfileResource($user);
    }
    
    public function getOrders(Request $request): ResourceCollection
    {
        $user = $request->user();

        $orders = $user->orders;

        OrderResource::withoutWrapping();
        return OrderResource::collection($orders);
    }
}