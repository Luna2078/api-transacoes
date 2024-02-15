<?php

namespace App\Services;

use App\Exceptions\AuthException;
use App\Services\Interfaces\AuthInterfaceService;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthService implements AuthInterfaceService
{
	/**
	 * @throws AuthException
	 */
	public function getAuth(): void
	{
		try {
			$auth = Http::withoutVerifying()->get(self::URL);
			if ($auth->status() != Response::HTTP_OK && $auth->json('message') != 'Autorizado') {
				throw new AuthException('getAuth', 'Failed to get authorization.', 'Failed to get authorization.');
			}
		} catch (Throwable $e) {
			throw new AuthException('getAuth', $e->getMessage(), 'Failed to get authorization.');
		}
	}
}
