<x-guest-layout title="Verifikasi Email">
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center py-12 px-4 relative"
         style="background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">

        <div class="absolute inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm"></div>

        <div class="relative z-10 max-w-2xl w-full bg-white bg-opacity-95 p-12 rounded-xl shadow-2xl text-center">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <!-- Heading -->
            <h2 class="font-playfair text-3xl font-bold text-gray-900 mb-4">
                Verifikasi Email Anda
            </h2>

            <p class="text-gray-600 mb-8 leading-relaxed">
                Terima kasih telah mendaftar! Sebelum melanjutkan, silakan verifikasi email Anda dengan mengklik link yang telah kami kirim ke
                <span class="font-semibold text-amber-600">{{ auth()->user()->email }}</span>
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded text-left">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Email verifikasi baru telah dikirim! Silakan cek inbox Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded text-left">
                    <p class="text-sm text-blue-700">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded text-left">
                    <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <!-- Resend Button -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="bg-amber-600 text-white px-8 py-3 rounded-lg hover:bg-amber-700 transition font-semibold tracking-wider uppercase shadow-lg">
                        Kirim Ulang Email
                    </button>
                </form>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium transition underline">
                        Logout
                    </button>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-8 p-6 bg-gray-50 rounded-lg text-left">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tidak menerima email?
                </h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Periksa folder <strong>Spam</strong> atau <strong>Junk</strong></li>
                    <li>• Pastikan alamat email Anda benar</li>
                    <li>• Tunggu beberapa menit dan coba kirim ulang</li>
                    <li>• Hubungi kami jika masih mengalami masalah</li>
                </ul>
            </div>
        </div>
    </div>
</x-guest-layout>
