<?php

namespace App\Services;

use App\Models\Payment;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Raiffeisen implements \App\Interfaces\Payment
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => getenv('PAYMENT_RAIFFEISEN_ENDPOINT')]);
    }

    public function makePayment(Payment $payment): ?string
    {
        $response = $this->client->post('qrs', [
            'json' => [
                'qrType' => 'QRDynamic',
                'amount' => (string)$payment->amount,
                'currency' => 'RUB',
                'order' => $payment->order->uuid,
                'qrExpirationDate' => Carbon::now()->addDay()->toIso8601String(),
                'sbpMerchantId' => getenv('PAYMENT_RAIFFEISEN_ID'),
                'redirectUrl' => $this->getSuccessUrl($payment),
            ],
        ]);
        $output = json_decode((string)$response->getBody(), true);
        if (!empty($output['qrId'])) {
            $payment->remote_id = $output['qrId'];
            $payment->link = $output['payload'];
            $payment->save();

            return $this::getQR($payment);
        }

        return null;
    }

    public function getPaymentStatus(Payment $payment): ?bool
    {
        try {
            $response = $this->client->get('qrs/' . $payment->remote_id, [
                'headers' => ['Authorization' => 'Bearer ' . getenv('PAYMENT_RAIFFEISEN_KEY')],
            ]);
            $output = json_decode((string)$response->getBody(), true);
            if ($output['qrStatus'] === 'PAID') {
                return true;
            }
            if (in_array($output['qrStatus'], ['CANCELLED', 'EXPIRED'])) {
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

    public static function getQR(Payment $payment): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $svg = (new Writer($renderer))->writeString($payment->link);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}