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
		Schema::table('wallets', function (Blueprint $table) {
			$table->foreign(['user_id'], 'fk_wallets_user')->references(['id'])
				->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('wallets', function (Blueprint $table) {
			$table->dropForeign('fk_wallets_user');
		});
	}
};
