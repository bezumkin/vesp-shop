<?php

namespace App\Models;

use App\Services\Mail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Ramsey\Uuid\Uuid;

/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int $address_id
 * @property string $num
 * @property float $total
 * @property float $cart
 * @property float $discount
 * @property float $weight
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read UserAddress $address
 * @property-read OrderProduct[] $orderProducts
 * @property-read Product[] $products
 */
class Order extends Model
{
    protected $fillable = ['user_id', 'address_id', 'discount', 'comment', 'created_at'];
    protected $casts = [
        'total' => 'float',
        'cart' => 'float',
        'discount' => 'float',
        'delivery' => 'float',
        'weight' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(static function (self $model) {
            if (!$model->uuid) {
                $model->uuid = (string)Uuid::uuid4();
            }
            if (!$model->created_at) {
                $model->created_at = Carbon::now();
            }
            if (!$model->num) {
                $time = $model->created_at->timestamp;
                $count = $model->newQuery()->where('created_at', 'LIKE', date('Y-m-', $time) . '%')->count();
                $model->num = implode('/', [date('ym', $time), $count + 1]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, OrderProduct::class, 'order_id', 'id', 'id', 'product_id');
    }

    public function calculate(): void
    {
        $cart = 0;
        $weight = 0;
        /** @var OrderProduct $orderProduct */
        foreach ($this->orderProducts()->cursor() as $orderProduct) {
            $cart += $orderProduct->amount * ($orderProduct->price - $orderProduct->discount);
            $weight += $orderProduct->amount * $orderProduct->weight;
        }
        $total = $cart - $this->discount;

        $this->weight = $weight;
        $this->cart = max($cart, 0);
        $this->total = max($total, 0);
        $this->save();
    }

    public function getData(): array
    {
        return [
            'order' => $this->toArray(),
            'user' => $this->user->toArray(),
            'address' => $this->address->toArray(),
            'products' => $this->orderProducts()
                ->with('product:id,uri', 'product.translations:product_id,lang,title', 'product.firstFile')
                ->get()
                ->toArray(),
        ];
    }

    public function sendEmails(?string $lang = 'ru'): ?string
    {
        $data = $this->getData();
        $data['lang'] = $lang;

        if ($emailAdmin = getenv('EMAIL_ADMIN')) {
            $mail = new Mail();
            $subject = getenv('EMAIL_SUBJECT_ORDER_NEW_ADMIN_' . strtoupper($lang));
            if ($error = $mail->send($emailAdmin, $subject, 'email-order-new-admin', $data)) {
                return $error;
            }
        }

        $subject = getenv('EMAIL_SUBJECT_ORDER_NEW_USER_' . strtoupper($lang));
        if ($error = $this->user->sendEmail($subject, 'email-order-new-user', $data)) {
            return $error;
        }

        return null;
    }
}