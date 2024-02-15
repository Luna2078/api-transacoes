<?php

namespace App\Services;

use App\Services\Interfaces\MailInterfaceService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MailService implements MailInterfaceService
{
	/**
	 * @throws Exception
	 */
	public function sendMail(): void
	{
		$sendMail = Http::withoutVerifying()->get(self::URL);
		if ($sendMail->status() != Response::HTTP_OK && $sendMail->json('message') != 'true') {
			Log::error('[MailService::sendMail]' . $sendMail->json('message'));
		}
	}
}
