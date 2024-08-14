<?php

namespace App\Http\Controllers\Api\Auth;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }
    public function register(UserRegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try{
            $data = $this->userService->register($request->validated());
            DB::commit();
            return ApiResponseClass::sendResponse($data, 'User created successfully.');
        }
        catch(\Exception $e){
            DB::rollBack();
            return ApiResponseClass::sendError($e->getMessage(), $e->getCode());
        }
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $this->userService->login($request->all());
        if($data){
            return ApiResponseClass::sendResponse($data, 'Login successfully.');
        }
        return ApiResponseClass::sendError('Login failed.', 401);
    }

    public function logout(): JsonResponse
    {
        $data = $this->userService->logout();
        if($data){
            return ApiResponseClass::sendResponse($data, 'Logout successfully.');
        }
        return ApiResponseClass::sendError('Logout failed.', 401);
    }

    public function me(): JsonResponse
    {
        if (!Auth::check()) {
            return ApiResponseClass::sendError('Unauthorized', 401);
        }
        $data = $this->userService->me();
        return ApiResponseClass::sendResponse(UserResource::make($data), 'User retrieved successfully.');
    }
}
