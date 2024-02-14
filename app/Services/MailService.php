<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MailService
{
	const URL = "https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6";
	public function __construct()
	{
	}
	
	/**
	 * @throws Exception
	 */
	public function sendMail(): void
	{
		$sendMail = Http::withoutVerifying()->get(self::URL);
		if ($sendMail->status() != Response::HTTP_OK && $sendMail->json('message') != 'true') {
			Log::error('[MailService::sendMail]' . $sendMail->json('message'));
			throw new Exception('Failed to send mail.', Response::HTTP_BAD_REQUEST);
		}
	}
}