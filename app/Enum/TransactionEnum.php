<?php

namespace App\Enum;

enum TransactionEnum: int
{
	case Store = 1;
	case Refund = 2;
	
	public function name(): string
	{
		return match ($this) {
			self::Store => 'Store',
			self::Refund => 'Refund'
		};
	}
}
