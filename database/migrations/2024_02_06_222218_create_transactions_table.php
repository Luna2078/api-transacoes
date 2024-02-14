<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->bigInteger('payer_id');
			$table->bigInteger('payee_id');
			$table->decimal('value');
			$table->tinyInteger('type')->comment('1 - Store, 2 - Refund');
			$table->bigInteger('transaction_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}
	
	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('transactions');
	}
};
