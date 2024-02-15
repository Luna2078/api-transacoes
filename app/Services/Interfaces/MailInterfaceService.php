<?php

namespace App\Services\Interfaces;

interface MailInterfaceService
{
	const URL = "https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6";
	
	public function sendMail(): void;
}