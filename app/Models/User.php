<?php

namespace App\Models;

use App\Enum\UserEnum;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $cpf_cnpj
 * @property string $email
 * @property string $password
 * @property UserEnum $type
 * @property float $balance
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class User extends Model
{
	use HasFactory, SoftDeletes;
	
	protected $primaryKey = 'id';
	protected $table = 'users';
	protected $keyType = 'int';

	protected $visible = [
		'name',
		'cpf_cnpj',
		'email',
		'type',
		'created_at',
		'updated_at',
		'wallet'
	];
	protected $fillable = [
		'name',
		'cpf_cnpj',
		'email',
		'password',
		'type',
	];
	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
		'type' => UserEnum::class
	];
	
	public function payerTransactions(): HasMany
	{
		return $this->hasMany(Transaction::class, 'payer_id', 'id');
	}
	public function payeeTransactions(): HasMany
	{
		return $this->hasMany(Transaction::class, 'payee_id', 'id');
	}
	public function wallet(): HasOne
	{
		return $this->hasOne(Wallet::class);
	}
}
