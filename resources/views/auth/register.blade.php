<x-guest-layout title="Register">
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative" 
         style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
        
        <div class="absolute inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm"></div>

        <div class="relative z-10 max-w-md w-full bg-white bg-opacity-95 p-10 rounded-xl shadow-2xl border border-gray-100">
            <div class="text-center mb-8">
                <h2 class="font-playfair text-3xl font-bold text-gray-900">Create Account</h2>
                <p class="text-gray-500 mt-2 text-sm">Join us for a luxury experience</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-gray-50" 
                           placeholder="John Doe">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-gray-50" 
                           placeholder="name@example.com">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-gray-50" 
                           placeholder="08123456789">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required autocomplete="new-password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-gray-50" 
                           placeholder="••••••••">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-gray-50" 
                           placeholder="••••••••">
                </div>

                <!-- CAPTCHA Placeholder -->
                <div class="p-4 bg-gray-100 rounded-lg border border-dashed border-gray-300 text-center text-xs text-gray-500">
                    [ Google reCAPTCHA v2 / hCaptcha Area ]
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-amber-600 text-white py-3.5 px-4 rounded-lg hover:bg-amber-700 transition font-semibold tracking-wider uppercase shadow-lg transform hover:-translate-y-0.5">
                    Register
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold transition">Login here</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>