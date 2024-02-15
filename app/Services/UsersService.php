<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Exceptions\NotFoundException;
use App\Exceptions\UserException;
use App\Models\User;
use App\Services\Interfaces\UsersInterfaceService;
use App\Services\Interfaces\WalletInterfaceService;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class UsersService implements UsersInterfaceService
{
	public function __construct(
		private readonly WalletInterfaceService $walletService
	)
	{
	}
	
	/**
	 * @throws NotFoundException
	 */
	public function getUserById(int $id): User
	{
		try {
			return User::query()->where('id', '=', $id)->get()->first();
		} catch (Exception $e) {
			throw new NotFoundException('getUserById', $e->getMessage(), 'User not found!');
		}
	}
	
	/**
	 * @throws UserException
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
			throw new UserException('storeUser', $e->getMessage(), 'Failed to create user.');
		}
	}
	
	/**
	 * @throws UserException
	 */
	public function updateUser(UserDTO $userDTO): bool
	{
		DB::beginTransaction();
		try {
			$user = User::query()->where('id', '=', $userDTO->id)->get()->first();
			$user->fill($userDTO->toArray());
			$wallet = $this->walletService->getWalletUserId($userDTO->id);
			$wallet->balance = $userDTO->balance;
			$wallet->saveOrFail();
			$user->saveOrFail();
			DB::commit();
			return true;
		} catch (Throwable $e) {
			DB::rollBack();
			throw new UserException('updateUser', $e->getMessage(), 'Failed to update user.');
		}
	}
	
	/**
	 * @throws UserException
	 */
	public function deleteUser(string $id): bool
	{
		DB::beginTransaction();
		try {
			$user = User::query()->where('id', '=', $id)->get()->first();
			$user->delete();
			$this->walletService->deleteWallet($id);
			DB::commit();
			return true;
		} catch (Throwable $e) {
			DB::rollBack();
			throw new UserException('deleteUser', $e->getMessage(), 'Failed to delete user.');
		}
	}
}
