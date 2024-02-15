<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Exceptions\WalletException;
use App\Models\Wallet;
use App\Services\Interfaces\WalletInterfaceService;
use Exception;
use Throwable;

class WalletService implements WalletInterfaceService
{
	/**
	 * @throws WalletException
	 */
	public function storeWallet(int $user_id, float $balance): void
	{
		try {
			$wallet = new Wallet();
			$wallet->user_id = $user_id;
			$wallet->balance = $balance;
			$wallet->saveOrFail();
		} catch (Throwable $e) {
			throw new WalletException('storeWallet', $e->getMessage(), 'Failed to create wallet.');
		}
	}
	
	/**
	 * @throws NotFoundException
	 */
	public function getWalletUserId(int $user_id): Wallet
	{
		try {
			return Wallet::query()->where('user_id', $user_id)->lockForUpdate()->get()->firstOrFail();
		} catch (Exception $e) {
			throw new NotFoundException('getWalletUserId', $e->getMessage(), 'Wallet not found!');
		}
	}
	
	/**
	 * @throws WalletException
	 */
	private function deposit(Wallet $wallet, float $value): void
	{
		try {
			$wallet->balance += $value;
			$wallet->saveOrFail();
		} catch (Throwable $e) {
			throw new WalletException('deposit', $e->getMessage(), 'Failed to deposit.');
		}
	}
	
	/**
	 * @throws WalletException
	 */
	private function withdraw(Wallet $wallet, float $value): void
	{
		try {
			if ($wallet->balance < $value) throw new WalletException('withdraw','Insufficient funds!');
			$wallet->balance -= $value;
			$wallet->saveOrFail();
		} catch (Throwable $e) {
			throw new WalletException('withdraw', $e->getMessage(), 'Failed to withdraw.');
		}
	}
	
	/**
	 * @throws NotFoundException
	 */
	public function deleteWallet(int $user_id): bool
	{
		try {
			$wallet = Wallet::query()->where('user_id', '=', $user_id)->get()->firstOrFail();
			return $wallet->delete();
		} catch (Throwable $e) {
			throw new NotFoundException('deleteWallet', $e->getMessage(), 'Wallet not found!');
		}
	}
	
	/**
	 * @throws NotFoundException|WalletException
	 */
	public function transfer(int $payer_id, int $payee_id, float $value): void
	{
		$payerWallet = $this->getWalletUserId($payer_id);
		$payeeWallet = $this->getWalletUserId($payee_id);
		$this->withdraw($payerWallet, $value);
		$this->deposit($payeeWallet, $value);
	}
}