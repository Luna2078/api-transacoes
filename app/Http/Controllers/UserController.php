<?php

namespace App\Http\Controllers;

use App\Factories\UserFactory;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UsersService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
	public function __construct(
		private readonly UsersService $usersService
	)
	{
	}
	
	
	public function getUserById(string $user_id)
	{
		try {
			return $this->usersService->getUserById($user_id);
		} catch (Exception $error) {
			return response()->json(['message' => $error->getMessage()], $error->getCode());
		}
	}
	
	public function storeUser(CreateUserRequest $request): JsonResponse|bool
	{
		try {
			return response()->json(
				$this->usersService->storeUser(UserFactory::toDTO($request->toArray())),
				Response::HTTP_CREATED);
		} catch (Exception $error) {
			return response()->json(['message' => $error->getMessage()], $error->getCode());
		}
	}
	
	public function updateUser(UpdateUserRequest $request): JsonResponse|bool
	{
		try {
			return $this->usersService->updateUser(UserFactory::toDTO($request->toArray()));
		} catch (Exception $error) {
			return response()->json(['message' => $error->getMessage()], $error->getCode());
		}
	}
	
	public function deleteUser(string $user_id): JsonResponse|bool
	{
		try {
			return $this->usersService->deleteUser($user_id);
		} catch (Exception $error) {
			return response()->json(['message' => $error->getMessage()], $error->getCode());
		}
	}
}
