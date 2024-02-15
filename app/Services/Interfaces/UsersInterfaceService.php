<?php

namespace App\Services\Interfaces;

use App\DTO\UserDTO;
use App\Models\User;

interface UsersInterfaceService
{
	public function getUserById(int $id): User;
	
	public function storeUser(UserDTO $userDTO): bool;
	
	public function updateUser(UserDTO $userDTO): bool;
}