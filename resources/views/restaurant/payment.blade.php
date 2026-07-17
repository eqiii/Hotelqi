<x-guest-layout title="Pembayaran Restoran">
    <section class="bg-gray-900 text-white py-20 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Restaurant</p>
        <h1 class="font-playfair text-4xl font-bold">Pembayaran</h1>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-[1fr_0.7fr] gap-8">
            <div class="bg-white rounded-3xl shadow border border-gray-100 p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h2>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between"><span>Subtotal</span><span>{{ format_rupiah($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between"><span>Tax</span><span>{{ format_rupiah($tax) }}</span></div>
                    <div class="flex justify-between font-semibold text-gray-900">
                        <span>Total</span><span>{{ format_rupiah($total) }}</span></div>
                </div>
                <p class="mt-6 text-sm text-gray-500">Pembayaran akan diproses setelah Anda menyelesaikan transaksi.
                    Pesanan akan dibuat hanya setelah pembayaran berhasil.</p>
            </div>

            <div class="bg-white rounded-3xl shadow border border-gray-100 p-8">
                @if (($checkout['details']['payment_method'] ?? 'midtrans') === 'midtrans' && $snapToken)
                    <div id="snap-container"></div>
                    <script src="{{ config('mdtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                        data-client-key="{{ config('mdtrans.client_key') }}"></script>
                    <script>
                        window.snap.pay('{{ $snapToken }}', {
                            onSuccess: function(result) {
                                window.location.href = '{{ route('user.restaurant.payment.finish') }}?order_id=' + result
                                    .order_id + '&transaction_status=' + result.transaction_status;
                            },
                            onPending: function(result) {
                                window.location.href = '{{ route('user.restaurant.payment.finish') }}?order_id=' + result
                                    .order_id + '&transaction_status=' + result.transaction_status;
                            },
                            onError: function() {
                                alert('Pembayaran gagal.');
                            }
                        });
                    </script>
                @else
                    <a href="{{ route('user.restaurant.payment.finish') }}?order_id=restaurant-manual&transaction_status=settlement"
                        class="block w-full bg-amber-600 hover:bg-amber-700 text-white text-center uppercase tracking-widest font-semibold py-3 rounded-xl transition">Selesaikan
                        Pembayaran</a>
                @endif
            </div>
        </div>
    </section>
</x-guest-layout>
