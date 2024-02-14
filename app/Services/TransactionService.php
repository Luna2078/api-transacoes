<?php

namespace App\Services;

use App\DTO\TransactionDTO;
use App\Enum\TransactionEnum;
use App\Enum\UserEnum;
use App\Models\Transaction;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TransactionService
{
	public function __construct(
		private readonly WalletService $walletService,
		private readonly AuthService $authService,
		private readonly MailService $mailService
	)
	{
	}
	
	/**
	 * @throws Exception
	 */
	public function getTransactionById(string $id): Transaction
	{
		try {
			return Transaction::query()->where('id', '=', $id)->get()->first();
		} catch (Exception) {
			throw new Exception('Transaction not found!');
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function storeTransaction(TransactionDTO $transactionDTO): bool
	{
		DB::beginTransaction();
		try {
			$transaction = new Transaction();
			$transaction->fill($transactionDTO->toArray());
			$transaction->type = TransactionEnum::Store;
			$payerWallet = $this->walletService->getWalletUserId($transactionDTO->payer_id);
			$payeeWallet = $this->walletService->getWalletUserId($transactionDTO->payee_id);
			$this->verifyPayer($payerWallet, $transactionDTO);
			$this->walletService->transfer($payerWallet->user_id, $payeeWallet->user_id, $transactionDTO->value);
			$payerWallet->saveOrFail();
			$payeeWallet->saveOrFail();
			$transaction->saveOrFail();
			$this->finishTransaction();
			DB::commit();
			return true;
		} catch (Throwable $e) {
			DB::rollBack();
			Log::error('[TransactionService::storeTransaction] ' . $e->getMessage());
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function refundTransaction(int $id): bool
	{
		DB::beginTransaction();
		try {
			$this->verifyTransaction($id);
			$transaction = Transaction::query()
				->where('id', '=', $id)
				->where('type', '=', TransactionEnum::Store)
				->get()->firstOrFail();
			$payerWallet = $this->walletService->getWalletUserId($transaction->payer_id);
			$payeeWallet = $this->walletService->getWalletUserId($transaction->payee_id);
			$refund = $this->createRefund($transaction);
			$this->walletService->transfer($payeeWallet->user_id, $payerWallet->user_id, $transaction->value);
			$payerWallet->saveOrFail();
			$payeeWallet->saveOrFail();
			$refund->saveOrFail();
			$this->finishTransaction();
			DB::commit();
			return true;
		} catch (ItemNotFoundException) {
			throw new Exception('Transaction not found!', Response::HTTP_NOT_FOUND);
		} catch (Throwable $e) {
			DB::rollBack();
			Log::error('[TransactionService::refundTransaction] ' . $e->getMessage());
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * @param Wallet $payer_wallet
	 * @param TransactionDTO $transactionDTO
	 * @return void
	 * @throws Exception
	 */
	public function verifyPayer(Wallet $payer_wallet, TransactionDTO $transactionDTO): void
	{
		if ($payer_wallet->user_id === $transactionDTO->payee_id) {
			throw new Exception('Payer and payee cannot be the same', Response::HTTP_BAD_REQUEST);
		}
		if ($payer_wallet->user->type === UserEnum::Shopkeeper) {
			throw new Exception('Shopkeeper cannot be payer', Response::HTTP_BAD_REQUEST);
		}
	}
	
	/**
	 * @return void
	 * @throws Exception
	 */
	public function finishTransaction(): void
	{
		try {
			$this->authService->getAuth();
			$this->mailService->sendMail();
		} catch (Throwable $e) {
			Log::error('[TransactionService::finishTransaction] ' . $e->getMessage());
			throw new Exception(
				'An error occurred while getting authorization or sending the notification.',
				Response::HTTP_BAD_REQUEST
			);
		}
	}
	
	/**
	 * @param $transaction
	 * @return Transaction
	 */
	public function createRefund($transaction): Transaction
	{
		$refund = new Transaction();
		$refund->payer_id = $transaction->payee_id;
		$refund->payee_id = $transaction->payee_id;
		$refund->value = $transaction->value;
		$refund->transaction_id = $transaction->id;
		$refund->type = TransactionEnum::Refund;
		return $refund;
	}
	
	/**
	 * @throws Exception
	 */
	private function verifyTransaction(int $transaction_id): void
	{
		$transaction = Transaction::query()->where('transaction_id', '=', $transaction_id)->get()->first();
		if (!empty($transaction) && $transaction->type === TransactionEnum::Refund) {
			throw new Exception('Transaction already refunded', Response::HTTP_BAD_REQUEST);
		}
	}
}