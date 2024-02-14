<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property float $balance
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property User $user
 */
class Wallet extends Model
{
    use HasFactory, SoftDeletes;
		
		protected $primaryKey = 'id';
		protected $keyType = 'int';
		protected $table = 'wallets';
		protected $fillable = [
			'user_id',
			'balance',
		];
		protected $visible = [
			'user_id',
			'balance',
			'created_at',
			'updated_at',
			'user',
		];
		protected $casts = [
			'balance' => 'float',
		];
		
		public function user(): BelongsTo
		{
			return $this->belongsTo(User::class);
		}
}
