<?php

namespace App\Enum;

enum UserEnum: int
{
	case Client = 1;
	case Shopkeeper = 2;
	
	public function name(): string
	{
		return match ($this) {
			self::Client => 'Client',
			self::Shopkeeper => 'Shopkeeper'
		};
	}
}
