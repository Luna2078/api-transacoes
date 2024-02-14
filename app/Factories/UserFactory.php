<?php

namespace App\Factories;

use App\DTO\UserDTO;
use App\Enum\UserEnum;

class UserFactory
{
	public static function toDTO(array $data): UserDTO
	{
		return new UserDTO(
			id: $data['id'] ?? null,
			name: $data['name'],
			cpf_cnpj: $data['cpf_cnpj'],
			email: $data['email'],
			type: UserEnum::from($data['type']),
			password: $data['password'],
			balance: $data['balance'],
		);
	}
}