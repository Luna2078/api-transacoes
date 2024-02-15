<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * The list of the inputs that are never flashed to the session on validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];
	
	/**
	 * Register the exception handling callbacks for the application.
	 */
	public function register(): void
	{
		$this->renderable(function (WalletException $e) {
			Log::error('[WalletService::' . $e->methodName . ']' . $e->internalError);
			return response()->json(['message' => $e->getMessage()], 400);
		});
		$this->renderable(function (NotFoundException $e) {
			Log::error('[NotFoundService::' . $e->methodName . ']' . $e->internalError);
			return response()->json(['message' => $e->getMessage()], 404);
		});
		$this->renderable(function (UserException $e)	{
			Log::error('[UserService::' . $e->methodName . ']' . $e->internalError);
			return response()->json(['message' => $e->getMessage()], 400);
		});
		$this->renderable(function (TransactionException $e)	{
			Log::error('[TransactionService::' . $e->methodName . ']' . $e->internalError);
			return response()->json(['message' => $e->getMessage()], 400);
		});
		$this->renderable(function (AuthException $e)	{
			Log::error('[AuthService::' . $e->methodName . ']' . $e->internalError);
			return response()->json(['message' => $e->getMessage()], 400);
		});
		$this->reportable(function (Throwable $e) {
		});
	}
}
