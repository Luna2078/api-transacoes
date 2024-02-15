<?php

namespace App\Services\Interfaces;

interface AuthInterfaceService
{
	const URL = "https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc";
	
	public function getAuth(): void;
}