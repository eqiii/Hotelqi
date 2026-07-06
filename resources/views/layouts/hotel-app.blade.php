<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? hotel_name() }} - Member Area</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }

        .hotel-sidebar {
            background: linear-gradient(180deg, #1a1208 0%, #2d1f0a 50%, #1a1208 100%);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: #c9a96e;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background: rgba(201, 169, 110, 0.1);
            color: #f5d78e;
            border-left-color: #c9a96e;
        }

        .sidebar-link.active {
            background: rgba(201, 169, 110, 0.15);
            color: #f5d78e;
            border-left-color: #c9a96e;
        }

        .hotel-topbar {
            background: #1a1208;
            border-bottom: 1px solid #3d2e15;
        }

        .hotel-content {
            background: #f8f5f0;
            min-height: 100vh;
        }

        .gold-btn {
            background: linear-gradient(135deg, #c9a96e 0%, #a87a3a 100%);
            color: white;
            border: none;
            transition: all 0.3s;
        }
        .gold-btn:hover {
            background: linear-gradient(135deg, #d4b97e 0%, #b88a4a 100%);
            box-shadow: 0 4px 20px rgba(201, 169, 110, 0.4);
            transform: translateY(-1px);
        }

        .card-hotel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            border: 1px solid #f0e8d8;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">
    {{-- ===== SIDEBAR ===== --}}
    <aside class="hotel-sidebar w-64 flex-shrink-0 flex flex-col" id="sidebar">
        {{-- Logo --}}
        <div class="p-6 border-b border-amber-900/30">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-600 flex items-center justify-center">
                    <span class="text-white font-bold text-lg font-playfair">H</span>
                </div>
                <div>
                    <p class="text-amber-200 font-playfair font-bold text-sm leading-tight">{{ hotel_name() }}</p>
                    <p class="text-amber-500 text-xs tracking-widest uppercase">Member Area</p>
                </div>
            </a>
        </div>

        {{-- User Info --}}
        <div class="p-4 border-b border-amber-900/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-700 flex items-center justify-center flex-shrink-0">
                    <span class="text-amber-200 font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-amber-100 font-medium text-sm truncate">{{ Auth::user()->name }}</p>
                    <p class="text-amber-500 text-xs truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 py-4 overflow-y-auto">
            <p class="px-5 py-2 text-amber-600 text-xs tracking-widest uppercase font-semibold">Menu</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('rooms') }}"
               class="sidebar-link">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Cari Kamar
            </a>

            <a href="{{ route('user.booking.history') }}"
               class="sidebar-link {{ request()->routeIs('user.booking.history') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Riwayat Booking
            </a>

            <a href="{{ route('user.restaurant.orders') }}"
               class="sidebar-link {{ request()->routeIs('user.restaurant.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Pesanan Restoran
            </a>

            <p class="px-5 py-2 mt-4 text-amber-600 text-xs tracking-widest uppercase font-semibold">Akun</p>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil Saya
            </a>

            <a href="{{ route('faq') }}" class="sidebar-link">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Bantuan & FAQ
            </a>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-amber-900/30">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="sidebar-link w-full text-left text-red-400 hover:text-red-300 border-l-transparent hover:border-l-red-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="hotel-topbar h-16 flex items-center justify-between px-6 flex-shrink-0">
            <div class="flex items-center gap-4">
                <h1 class="text-amber-200 font-playfair text-lg font-semibold">
                    @isset($pageTitle)
                        {{ $pageTitle }}
                    @else
                        Member Area
                    @endisset
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-amber-400 hover:text-amber-200 text-sm transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Landing Page
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto hotel-content p-6 lg:p-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-4 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error') || session('warning'))
                <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-5 py-4 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('error') ?? session('warning') }}</p>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
