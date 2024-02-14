<?php

namespace App\Services;

use App\Models\Wallet;
use Exception;
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
		$wallet = new Wallet();
		$wallet->user_id = $user_id;
		$wallet->balance = $balance;
		$wallet->saveOrFail();
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
		$wallet->balance += $value;
		return $wallet->saveOrFail();
	}
	
	/**
	 * @throws Throwable
	 * @throws Exception
	 */
	public function withdraw(Wallet $wallet, float $value): bool
	{
		if ($wallet->balance < $value) throw new Exception('Insufficient funds!', Response::HTTP_BAD_REQUEST);
		$wallet->balance -= $value;
		return $wallet->saveOrFail();
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