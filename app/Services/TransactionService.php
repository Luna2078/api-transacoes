<?php

namespace App\Services;

use App\DTO\TransactionDTO;
use App\Enum\TransactionEnum;
use App\Enum\UserEnum;
use App\Exceptions\AuthException;
use App\Exceptions\NotFoundException;
use App\Exceptions\TransactionException;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Interfaces\AuthInterfaceService;
use App\Services\Interfaces\MailInterfaceService;
use App\Services\Interfaces\TransactionInterfaceService;
use App\Services\Interfaces\WalletInterfaceService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ItemNotFoundException;
use Throwable;

class TransactionService implements TransactionInterfaceService
{
	public function __construct(
		private readonly WalletInterfaceService $walletService,
		private readonly AuthInterfaceService   $authService,
		private readonly MailInterfaceService   $mailService
	)
	{
	}
	
	/**
	 * @throws NotFoundException
	 */
	public function getTransactionById(int $id): Transaction
	{
		try {
			return Transaction::query()->where('id', '=', $id)->get()->first();
		} catch (Exception $e) {
			throw new NotFoundException('getTransactionById', $e->getMessage(), 'Transaction not found!');
		}
	}
	
	/**
	 * @throws TransactionException
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
			throw new TransactionException('storeTransaction', $e->getMessage(), 'Failed to create transaction.');
		}
	}
	
	/**
	 * @throws TransactionException|NotFoundException
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
		} catch (ItemNotFoundException $e) {
			throw new NotFoundException('refundTransaction', $e->getMessage(), 'Transaction not found!');
		} catch (Throwable $e) {
			DB::rollBack();
			throw new TransactionException('refundTransaction', $e->getMessage(), 'Failed to refund transaction.');
		}
	}
	
	/**
	 * @param Wallet $payer_wallet
	 * @param TransactionDTO $transactionDTO
	 * @return void
	 * @throws Exception
	 */
	private function verifyPayer(Wallet $payer_wallet, TransactionDTO $transactionDTO): void
	{
		if ($payer_wallet->user_id === $transactionDTO->payee_id) {
			throw new Exception('Payer and payee cannot be the same.');
		}
		if ($payer_wallet->user->type === UserEnum::Shopkeeper) {
			throw new Exception('Shopkeeper cannot be payer.');
		}
	}
	
	/**
	 * @return void
	 * @throws AuthException
	 */
	private function finishTransaction(): void
	{
		$this->authService->getAuth();
		$this->mailService->sendMail();
	}
	
	/**
	 * @param $transaction
	 * @return Transaction
	 */
	private function createRefund($transaction): Transaction
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
			throw new Exception('Transaction already refunded');
		}
	}
}