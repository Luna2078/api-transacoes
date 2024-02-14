<?php

namespace App\Services;

use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class WalletService
{
	public function __construct()
	{
	}
	
	/**
	 * @throws Throwable
	 */
	public function storeWallet(int $user_id, float $balance = 0): void
	{
		try {
			$wallet = new Wallet();
			$wallet->user_id = $user_id;
			$wallet->balance = $balance;
			$wallet->saveOrFail();
		} catch (Exception $e) {
			Log::error('[WalletService::storeWallet]' . $e->getMessage());
			throw new Exception('Failed to create wallet.', Response::HTTP_BAD_REQUEST);
		}
	}
	
	public function getWalletUserId(int $user_id): Wallet
	{
		return Wallet::query()->where('user_id', $user_id)->lockForUpdate()->get()->first();
	}
	
	/**
	 * @throws Throwable
	 */
	public function deposit(Wallet $wallet, float $value): bool
	{
		try {
			$wallet->balance += $value;
			return $wallet->saveOrFail();
		} catch (Exception $e) {
			Log::error('[WalletService::deposit]' . $e->getMessage());
			throw new Exception('Failed to deposit.', Response::HTTP_BAD_REQUEST);
		}
	}
	
	/**
	 * @throws Throwable
	 * @throws Exception
	 */
	public function withdraw(Wallet $wallet, float $value): bool
	{
		try {
			if ($wallet->balance < $value) throw new Exception('Insufficient funds!', Response::HTTP_BAD_REQUEST);
			$wallet->balance -= $value;
			return $wallet->saveOrFail();
		} catch (Exception $e) {
			Log::error('[WalletService::withdraw]' . $e->getMessage());
			throw new Exception('Failed to withdraw.', Response::HTTP_BAD_REQUEST);
		}
	}
	
	/**
	 * @throws Throwable
	 */
	public function transfer(int $payer_id, int $payee_id, float $value): void
	{
		$payerWallet = $this->getWalletUserId($payer_id);
		$payeeWallet = $this->getWalletUserId($payee_id);
		$this->withdraw($payerWallet, $value);
		$this->deposit($payeeWallet, $value);
	}
}