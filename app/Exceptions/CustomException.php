<?php

namespace App\Exceptions;

use DomainException;

abstract class CustomException extends DomainException
{
	public function __construct(
		readonly string $methodName,
		readonly string $internalError,
		string          $message = 'Custom Exception'
	)
	{
		parent::__construct($message);
	}
}