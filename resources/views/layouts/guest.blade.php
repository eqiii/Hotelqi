<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? hotel_name() }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .hero-bg {
            background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
    <!-- PWA Settings -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#c9a96e">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.error('Service Worker registration failed', err));
            });
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900">

    <!-- Navbar -->
    <nav class="absolute top-0 left-0 right-0 z-50 bg-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-lg">H</span>
                    </div>
                    <span class="text-white font-playfair text-2xl font-bold tracking-wider">{{ hotel_name() }}</span>
                </div>

                <!-- Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-white hover:text-amber-400 text-sm tracking-widest uppercase transition">Home</a>
                    <a href="{{ route('home') }}#about" class="text-white hover:text-amber-400 text-sm tracking-widest uppercase transition">About</a>
                    <a href="{{ route('rooms') }}" class="text-white hover:text-amber-400 text-sm tracking-widest uppercase transition">Rooms</a>
                    <a href="{{ route('restaurant') }}" class="text-white hover:text-amber-400 text-sm tracking-widest uppercase transition">Restaurant</a>
                    <a href="{{ route('faq') }}" class="text-white hover:text-amber-400 text-sm tracking-widest uppercase transition">FAQ</a>
                    <a href="{{ route('home') }}#contact" class="text-white hover:text-amber-400 text-sm tracking-widest uppercase transition">Contact</a>
                </div>

                <!-- Booking Button -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-amber-600 text-white px-6 py-2.5 text-xs tracking-widest uppercase hover:bg-amber-700 transition font-semibold">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-amber-400 text-sm tracking-widest uppercase transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-amber-600 text-white px-6 py-2.5 text-xs tracking-widest uppercase hover:bg-amber-700 transition font-semibold">Booking Online</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white" id="contact">
        <div class="max-w-7xl mx-auto py-16 px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">H</span>
                        </div>
                        <span class="font-playfair text-2xl font-bold tracking-wider">{{ hotel_name() }}</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed max-w-md">{{ $hotelProfile->description ?? 'Hotel mewah dengan pelayanan terbaik untuk kenyamanan menginap Anda.' }}</p>
                </div>
                <div>
                    <h4 class="text-amber-400 text-sm tracking-widest uppercase mb-4 font-semibold">Kontak</h4>
                    <p class="text-gray-400 mb-2">{{ $hotelProfile->address ?? 'Jl. Hotel Mewah No. 123' }}</p>
                    <p class="text-gray-400 mb-2">Telp: {{ $hotelProfile->phone ?? '08123456789' }}</p>
                    <p class="text-gray-400">Email: {{ $hotelProfile->email ?? 'info@hotel.com' }}</p>
                </div>
                <div>
                    <h4 class="text-amber-400 text-sm tracking-widest uppercase mb-4 font-semibold">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-amber-600 transition">FB</a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-amber-600 transition">IG</a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-amber-600 transition">TW</a>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ hotel_name() }}. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
