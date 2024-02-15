<?php

namespace App\Http\Controllers;

use App\Factories\TransactionFactory;
use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use App\Services\Interfaces\TransactionInterfaceService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
	public function __construct(
		private readonly TransactionInterfaceService $transactionService
	)
	{
	}
	
	public function storeTransaction(CreateTransactionRequest $request): JsonResponse|bool
	{
		return response()->json(
			$this->transactionService->storeTransaction(TransactionFactory::toDTO($request->toArray())),
			Response::HTTP_CREATED);
	}
	
	public function refundTransaction(int $transaction_id): JsonResponse|bool
	{
		return response()->json(
			$this->transactionService->refundTransaction($transaction_id),
			Response::HTTP_CREATED);
	}
	
	public function getTransactionById(string $transaction_id): JsonResponse|Transaction
	{
		return response()->json($this->transactionService->getTransactionById($transaction_id),
			Response::HTTP_OK);
	}
}
