<?php

namespace App\Factories;

use App\DTO\TransactionDTO;

class TransactionFactory
{
	public static function toDTO(array $data): TransactionDTO
	{
		return new TransactionDTO(
			payer_id: $data['payer'],
			payee_id: $data['payee'],
			value: $data['value'],
		);
	}
}