<x-guest-layout title="Login">
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative"
        style="background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm"></div>

        <!-- Login Card -->
        <div
            class="relative z-10 max-w-md w-full bg-white bg-opacity-95 p-10 rounded-xl shadow-2xl border border-gray-100">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-amber-600 rounded-full mb-4">
                    <span class="text-white font-bold text-xl">H</span>
                </div>
                <h2 class="font-playfair text-3xl font-bold text-gray-900">Welcome Back</h2>
                <p class="text-gray-500 mt-2 text-sm">Login to access your reservation</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                    <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-gray-50"
                        placeholder="name@example.com">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition bg-gray-50"
                        placeholder="••••••••">
                </div>

                <!-- Login As -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Login As</label>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="login_as" value="guest"
                                {{ old('login_as', 'guest') === 'guest' ? 'checked' : '' }}
                                class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-600">Customer</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="login_as" value="admin"
                                {{ old('login_as') === 'admin' ? 'checked' : '' }}
                                class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-600">Staff</span>
                        </label>
                    </div>
                    @error('login_as')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-amber-600 hover:text-amber-700 font-medium transition">Forgot
                            password?</a>
                    @endif
                </div>


                <div class="mt-4">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}">
                    </div>

                    @error('g-recaptcha-response')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-amber-600 text-white py-3.5 px-4 rounded-lg hover:bg-amber-700 transition font-semibold tracking-wider uppercase shadow-lg transform hover:-translate-y-0.5">
                    Log In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="text-amber-600 hover:text-amber-700 font-semibold transition">Register here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</x-guest-layout>
