<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class CreateRazorpayOrderAction
{
    public function handle(Payment $payment): string
    {
        $response = Http::withBasicAuth(
            config('services.razorpay.key'),
            config('services.razorpay.secret'),
        )
            ->acceptJson()
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => (int) round(((float) $payment->amount) * 100),
                'currency' => $payment->currency,
                'receipt' => 'rcpt_' . $payment->id,
                'notes' => [
                    'payment_id' => (string) $payment->id,
                    'package_id' => (string) $payment->package_id,
                ],
            ])
            ->throw();

        return (string) $response->json('id');
    }
}
