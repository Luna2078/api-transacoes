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
		Schema::create('users', function (Blueprint $table) {
			$table->bigInteger('id', true);
			$table->string('name', 255);
			$table->string('cpf_cnpj', 14)->unique();
			$table->string('email', 255)->unique();
			$table->string('password', 255);
			$table->tinyInteger('type')->default(1)->comment('1 - Payer, 2 - Payee');
			$table->timestamps();
			$table->softDeletes();
		});
	}
	
	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('users');
	}
};
