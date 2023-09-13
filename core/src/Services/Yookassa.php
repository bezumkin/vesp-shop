<?php

namespace App\Services;

use App\Models\Payment;
use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;

class Yookassa implements \App\Interfaces\Payment
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => getenv('PAYMENT_YOOKASSA_ENDPOINT'),
            'auth' => [
                getenv('PAYMENT_YOOKASSA_USER'),
                getenv('PAYMENT_YOOKASSA_PASSWORD'),
            ],
        ]);
    }

    public function makePayment(Payment $payment): ?string
    {
        $url = $this->getSuccessUrl($payment);
        $response = $this->client->post('payments', [
            'headers' => ['Idempotence-Key' => (string)Uuid::uuid5(Uuid::NAMESPACE_URL, $url)],
            'json' => [
                'amount' => [
                    'value' => $payment->amount,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => $url,
                ],
                'capture' => true,
                'description' => $payment->order->num,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order->id,
                ],
            ],
        ]);
        $output = json_decode((string)$response->getBody(), true);
        if (!empty($output['id'])) {
            $payment->remote_id = $output['id'];
            $payment->link = $output['confirmation']['confirmation_url'];
            $payment->save();

            return $payment->link;
        }

        return null;
    }

    public function getPaymentStatus(Payment $payment): ?bool
    {
        try {
            $url = $this->getSuccessUrl($payment);
            $response = $this->client->get('payments/' . $payment->remote_id, [
                'headers' => ['Idempotence-Key' => (string)Uuid::uuid5(Uuid::NAMESPACE_URL, $url)],
            ]);
            $output = json_decode((string)$response->getBody(), true);

            if ($output['status'] === 'succeeded') {
                return true;
            }
            if ($output['status'] === 'canceled') {
                return false;
            }
        } catch (\Throwable  $e) {
        }

        return null;
    }

    public function getSuccessUrl(Payment $payment): string
    {
        return rtrim(getenv('SITE_URL'), '/') . '/orders/' . $payment->order->uuid;
    }
}