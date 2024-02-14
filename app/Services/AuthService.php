<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
	const URL = "https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc";
	public function __construct()
	{
	}
	
	/**
	 * @throws Exception
	 */
	public function getAuth(): void
	{
		try {
			$auth = Http::withoutVerifying()->get(self::URL);
			if ($auth->status() != Response::HTTP_OK && $auth->json('message') != 'Autorizado') {
				throw new Exception('Failed to get authorization.', Response::HTTP_UNAUTHORIZED);
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
}