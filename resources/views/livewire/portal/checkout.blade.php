<div class="max-w-3xl mx-auto px-6 py-14" x-data @initiate-razorpay-checkout.window="openRazorpayCheckout($event.detail)">
    <!-- Header -->
    <div class="text-center mb-8">
        <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Secure Checkout</span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight mt-2">Complete your purchase</h1>
        <p class="text-sm text-slate-500 mt-3">Unlock the full test series instantly after payment.</p>
    </div>

    <!-- Error Banner -->
    @if($errorMessage)
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-2xl flex items-start gap-3">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.3 16a2 2 0 001.75 3z"/></svg>
            <span>{{ $errorMessage }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <!-- Order Summary -->
        <div class="md:col-span-3 bg-white border border-slate-200 rounded-3xl p-7 shadow-xs space-y-5">
            <h2 class="text-xs font-black text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-3">Order Summary</h2>

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $package->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $package->description }}</p>
                </div>
                <span class="shrink-0 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">Series</span>
            </div>

            <div class="space-y-2 pt-1 border-t border-slate-100 text-xs text-slate-600">
                <div class="flex items-center justify-between">
                    <span>Mock tests included</span>
                    <strong class="text-slate-900">{{ $package->tests_count }}</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span>Access validity</span>
                    <strong class="text-slate-900">{{ $package->validityLabel() }}</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span>Price</span>
                    <strong class="text-slate-900">&#8377;{{ number_format((float) $package->price, 0) }}</strong>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                <span class="text-sm font-bold text-slate-700">Total payable</span>
                <span class="text-3xl font-black text-slate-900">&#8377;{{ number_format((float) $package->price, 0) }}</span>
            </div>
        </div>

        <!-- Pay Panel -->
        <div class="md:col-span-2 bg-white border border-slate-200 rounded-3xl p-7 shadow-xs flex flex-col justify-between h-fit">
            <div class="space-y-3">
                <h2 class="text-xs font-black text-slate-500 uppercase tracking-wider">Payment</h2>
                <p class="text-xs text-slate-500 leading-relaxed">Pay securely via UPI, cards, netbanking or wallets — powered by Razorpay.</p>
                <div class="flex items-center gap-2 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    256-bit SSL encrypted
                </div>
            </div>

            <button type="button" wire:click="initiatePayment" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-not-allowed" wire:target="initiatePayment"
                class="mt-6 w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 gradient-btn-primary hover:opacity-95 text-white text-sm font-bold rounded-full shadow-md shadow-purple-500/20 transition">
                <svg wire:loading.remove wire:target="initiatePayment" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <svg wire:loading wire:target="initiatePayment" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                <span wire:loading.remove wire:target="initiatePayment">Pay &#8377;{{ number_format((float) $package->price, 0) }}</span>
                <span wire:loading wire:target="initiatePayment">Starting secure payment&hellip;</span>
            </button>

            <a href="{{ route('portal.series') }}" class="mt-3 text-center text-[11px] font-bold text-slate-400 hover:text-slate-600 transition">&larr; Back to test series</a>
        </div>
    </div>

    @push('scripts')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            function openRazorpayCheckout(config) {
                if (typeof Razorpay === 'undefined') {
                    @this.call('gatewayLoadFailed');
                    return;
                }
                const options = {
                    ...config,
                    handler: function (response) {
                        @this.call('verifyPayment', response);
                    },
                    modal: {
                        ondismiss: function () {
                            @this.call('paymentCancelled');
                        },
                    },
                };
                new Razorpay(options).open();
            }
        </script>
    @endpush
</div>
