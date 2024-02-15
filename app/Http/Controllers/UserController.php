<?php

namespace App\Http\Controllers;

use App\Factories\UserFactory;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\Interfaces\UsersInterfaceService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
	public function __construct(
		private readonly UsersInterfaceService $usersService
	)
	{
	}
	
	public function getUserById(string $user_id): User|JsonResponse
	{
		return response()->json($this->usersService->getUserById($user_id),
			Response::HTTP_OK);
	}
	
	public function storeUser(CreateUserRequest $request): JsonResponse|bool
	{
		return response()->json(
			$this->usersService->storeUser(UserFactory::toDTO($request->toArray())),
			Response::HTTP_CREATED);
	}
	
	public function updateUser(UpdateUserRequest $request): JsonResponse|bool
	{
		return response()->json($this->usersService->updateUser(UserFactory::toDTO($request->toArray())),
			Response::HTTP_OK);
	}
	
	public function deleteUser(string $user_id): JsonResponse|bool
	{
		return response()->json($this->usersService->deleteUser($user_id),
			Response::HTTP_OK);
	}
}
