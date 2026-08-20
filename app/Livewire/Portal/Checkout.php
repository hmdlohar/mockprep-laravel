<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Actions\CreateRazorpayOrderAction;
use App\Actions\VerifyAndGrantPackageAccessAction;
use App\Enums\PaymentStatus;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Checkout extends Component
{
    public Package $package;

    public string $errorMessage = '';

    public function mount(Package $package): void
    {
        abort_unless($package->is_published, 404);

        if ($package->is_free) {
            $this->redirectRoute('portal.series');

            return;
        }

        if (Auth::user()->ownsPackage($package)) {
            $this->redirectRoute('portal.series');

            return;
        }

        $this->package = $package->loadCount('tests');
    }

    public function initiatePayment(CreateRazorpayOrderAction $createOrder): void
    {
        $user = Auth::user();

        if ($user->ownsPackage($this->package)) {
            $this->redirectRoute('portal.series');

            return;
        }

        $this->errorMessage = '';

        $payment = Payment::create([
            'user_id' => $user->id,
            'package_id' => $this->package->id,
            'razorpay_order_id' => 'pending_' . uniqid(),
            'amount' => $this->package->price,
            'currency' => 'INR',
            'status' => PaymentStatus::CREATED,
        ]);

        try {
            $razorpayOrderId = $createOrder->handle($payment);
            $payment->update(['razorpay_order_id' => $razorpayOrderId]);
        } catch (\Throwable $e) {
            $payment->markFailed();
            report($e);
            $this->errorMessage = 'We could not start the payment. Please check your connection and try again.';

            return;
        }

        $this->dispatch('initiate-razorpay-checkout', [
            'key' => config('services.razorpay.key'),
            'amount' => (int) round(((float) $this->package->price) * 100),
            'currency' => 'INR',
            'name' => 'On Your Mocks',
            'description' => $this->package->title,
            'order_id' => $razorpayOrderId,
            'prefill' => [
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->phone ?? '',
            ],
            'theme' => ['color' => '#7c3aed'],
        ]);
    }

    /**
     * @param  array{razorpay_order_id: string, razorpay_payment_id: string, razorpay_signature: string}  $response
     */
    public function verifyPayment(array $response, VerifyAndGrantPackageAccessAction $verify): void
    {
        $payment = Payment::where('razorpay_order_id', $response['razorpay_order_id'] ?? '')
            ->where('user_id', Auth::id())
            ->first();

        if (!$payment || !$verify->handle($payment, $response)) {
            $this->errorMessage = 'Payment verification failed. If money was deducted it will be auto-refunded by Razorpay within 5-7 days. Please contact support with order ID ' . ($response['razorpay_order_id'] ?? 'N/A');

            return;
        }

        session()->flash('payment_success', 'Payment successful! "' . $this->package->title . '" is now unlocked. Happy prepping!');
        $this->redirectRoute('portal.series');
    }

    public function paymentCancelled(): void
    {
        Payment::where('user_id', Auth::id())
            ->where('status', PaymentStatus::CREATED)
            ->get()
            ->each(fn (Payment $p) => $p->markFailed());

        $this->errorMessage = 'Payment was cancelled. You can try again whenever you are ready.';
    }

    public function gatewayLoadFailed(): void
    {
        $this->errorMessage = 'Payment gateway could not load. Please check your internet connection and try again.';
    }

    public function render()
    {
        return view('livewire.portal.checkout');
    }
}
