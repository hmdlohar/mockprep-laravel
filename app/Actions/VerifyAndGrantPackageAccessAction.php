<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class VerifyAndGrantPackageAccessAction
{
    /**
     * Verifies the Razorpay signature and unlocks the package for the user.
     *
     * @param  Payment  $payment  Payment row matching the razorpay_order_id.
     * @param  array{razorpay_order_id: string, razorpay_payment_id: string, razorpay_signature: string}  $response
     * @return bool True when the user now has access (paid or already owned).
     */
    public function handle(Payment $payment, array $response): bool
    {
        if ($payment->razorpay_order_id !== $response['razorpay_order_id']) {
            return false;
        }

        $expected = hash_hmac(
            'sha256',
            $response['razorpay_order_id'] . '|' . $response['razorpay_payment_id'],
            (string) config('services.razorpay.secret'),
        );

        if (!hash_equals((string) $expected, (string) $response['razorpay_signature'])) {
            $payment->markFailed();

            return false;
        }

        DB::transaction(function () use ($payment, $response): void {
            $package = $payment->package;
            $expiresAt = $package->validity_days
                ? now()->addDays($package->validity_days)
                : null;

            $payment->markPaid($response['razorpay_payment_id'], $response['razorpay_signature']);

            $payment->user->packages()->syncWithoutDetaching([
                $package->id => ['expires_at' => $expiresAt],
            ]);
        });

        return $payment->fresh()->status === PaymentStatus::PAID;
    }
}
