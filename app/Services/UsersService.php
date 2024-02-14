<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UsersService
{
	public function __construct(
		private readonly WalletService $walletService
	)
	{
	}
	
	/**
	 * @throws Exception
	 */
	public function getUserById(int $id): User
	{
		try {
			return User::query()->where('id', '=', $id)->get()->first();
		} catch (Exception) {
			throw new Exception('User not found!');
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function storeUser(UserDTO $userDTO): bool
	{
		DB::beginTransaction();
		try {
			$user = new User();
			$user->fill($userDTO->toArray());
			$user->saveOrFail();
			$this->walletService->storeWallet($user->id, $userDTO->balance);
			DB::commit();
			return true;
		} catch (Throwable $e) {
			DB::rollBack();
			Log::error('[UsersService::storeUser]' . $e->getMessage());
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function updateUser(UserDTO $userDTO): bool
	{
		DB::beginTransaction();
		try {
			$user = User::query()->where('id', '=', $userDTO->id)->get()->first();
			$user->fill($userDTO->toArray());
			$user->saveOrFail();
			DB::commit();
			return true;
		} catch (Throwable $e) {
			DB::rollBack();
			Log::error('[UsersService::updateUser]' . $e->getMessage());
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function deleteUser(string $id): bool
	{
		DB::beginTransaction();
		try {
			$user = User::query()->where('id', '=', $id)->get()->first();
			$user->delete();
			DB::commit();
			return true;
		} catch (Throwable $e) {
			DB::rollBack();
			Log::error('[UsersService::deleteUser]' . $e->getMessage());
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
}
