<?php

namespace App\Models;

use App\Interfaces\Payment as PaymentInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $service
 * @property float $amount
 * @property ?bool $paid
 * @property string $link
 * @property string $remote_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $paid_at
 *
 * @property-read User $user
 * @property-read Order $order
 */
class Payment extends Model
{
    protected $casts = [
        'amount' => 'float',
        'paid' => 'boolean',
        'paid_at' => 'datetime',
    ];
    protected $guarded = ['paid', 'paid_at'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function getService(): PaymentInterface
    {
        $service = '\App\Services\\' . ucfirst($this->service);

        return new $service();
    }

    public function checkStatus(): ?bool
    {
        if ($this->paid === null) {
            $timeout = getenv('PAYMENT_TIMEOUT') ?: 24;
            $service = $this->getService();
            $status = $service->getPaymentStatus($this);
            if ($status === true) {
                $this->paid = true;
                $this->paid_at = time();
                $this->save();
            } elseif ($status === false || $this->created_at->addHours($timeout) < Carbon::now()) {
                $this->paid = false;
                $this->save();
            }
        }

        return $this->paid;
    }

    public function getLink(): ?array
    {
        if ($link = $this->getService()->makePayment($this)) {
            return strpos($link, 'data:image/') === 0
                ? ['qr' => $link]
                : ['redirect' => $link];
        }

        return null;
    }
}