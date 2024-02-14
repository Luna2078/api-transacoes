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
		Schema::table('transactions', function (Blueprint $table) {
			$table->foreign(['payer_id'], 'fk_transactions_users_payer')->references(['id'])->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign(['payee_id'], 'fk_transactions_users_payee')->references(['id'])->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('transactions', function (Blueprint $table) {
			$table->dropForeign('fk_transactions_users_payer');
			$table->dropForeign('fk_transactions_users_payee');
		});
	}
};
