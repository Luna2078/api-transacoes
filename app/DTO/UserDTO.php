<?php

namespace App\DTO;

use App\Enum\UserEnum;

class UserDTO
{
	public function __construct(
		public readonly ?int $id,
		public readonly ?string $name,
		public readonly ?string $cpf_cnpj,
		public readonly ?string $email,
		public readonly ?UserEnum $type,
		public readonly ?string $password,
		public readonly ?float $balance,
	)
	{
	}
	
	public function toArray()
	{
		return [
			'id' => $this?->id,
			'name' => $this?->name,
			'cpf_cnpj' => $this?->cpf_cnpj,
			'email' => $this?->email,
			'type' => $this?->type,
			'password' => $this?->password,
			'balance' => $this?->balance,
		];
	}
}