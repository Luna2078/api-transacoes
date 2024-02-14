<?php

namespace App\Http\Controllers;

use App\Factories\TransactionFactory;
use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
	public function __construct(
		private readonly TransactionService $transactionService
	)
	{
	}
	
	public function storeTransaction(CreateTransactionRequest $request): JsonResponse|bool
	{
		try {
			return response()->json(
				$this->transactionService->storeTransaction(TransactionFactory::toDTO($request->toArray())),
				Response::HTTP_CREATED);
		} catch (Exception $error) {
			return response()->json(['message' => $error->getMessage()], $error->getCode());
		}
	}
	
	public function refundTransaction(int $transaction_id): JsonResponse|bool
	{
		try {
			return response()->json(
				$this->transactionService->refundTransaction($transaction_id),
				Response::HTTP_CREATED);
		} catch (Exception $error) {
			return response()->json(['message' => $error->getMessage()], $error->getCode());
		}
	}
	
	public function getTransactionById(string $transaction_id): JsonResponse|Transaction
	{
		try {
			return $this->transactionService->getTransactionById($transaction_id);
		} catch (Exception $error) {
			return response()->json(['message' => $error->getMessage()], $error->getCode());
		}
	}
}
