<?php

namespace App\Services\Interfaces;

use App\DTO\TransactionDTO;
use App\Models\Transaction;

interface TransactionInterfaceService
{
	public function getTransactionById(int $id): Transaction;
	
	public function storeTransaction(TransactionDTO $transactionDTO): bool;
	
	public function refundTransaction(int $id): bool;
}