<?php

namespace App\Services\Interfaces;

use App\Models\Wallet;

interface WalletInterfaceService
{
	public function storeWallet(int $user_id, float $balance): void;
	
	public function getWalletUserId(int $user_id): Wallet;
	
	public function deleteWallet(int $user_id): bool;
	
	public function transfer(int $payer_id, int $payee_id, float $value): void;
}