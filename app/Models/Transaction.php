<?php

namespace App\Models;

use App\Enum\TransactionEnum;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $payer_id
 * @property int $payee_id
 * @property int $transaction_id
 * @property float $value
 * @property TransactionEnum $type
 * @property User $payer
 * @property User $payee
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Transaction extends Model
{
    use HasFactory, SoftDeletes;
		protected $primaryKey = 'id';
		protected $table = 'transactions';
		protected $keyType = 'int';
		protected $visible = [
			'value',
			'created_at',
			'updated_at',
			'payer',
			'payee',
			'type',
			'payee_id',
			'payer_id',
			'transaction_id'
			];
		protected $fillable = [
			'payer_id',
			'payee_id',
			'value'
		];
		protected $casts = [
			'created_at' => 'datetime',
			'updated_at' => 'datetime',
			'deleted_at' => 'datetime',
			'type' => TransactionEnum::class
		];
		
		public function payer(): BelongsTo
		{
			return $this->belongsTo(User::class, 'payer_id', 'id');
		}
		public function payee(): BelongsTo
		{
			return $this->belongsTo(User::class, 'payee_id', 'id');
		}
}
