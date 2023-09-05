<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

/**
 * @property string $id
 * @property ?int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read CartProduct[] $cartProducts
 */
class Cart extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['user_id'];

    protected static function booted(): void
    {
        static::saving(static function (self $model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cartProducts(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }
}