<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $titleText = $title ?? 'Manager Panel';
    @endphp

    <title>{{ $titleText }} - Manager Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        /* Sidebar — dark teal/slate untuk membedakan dari admin gold */
        .manager-sidebar {
            background: linear-gradient(180deg, #0f2027 0%, #203a43 50%, #0f2027 100%);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: #7dd3fc;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background: rgba(125, 211, 252, 0.1);
            color: #bae6fd;
            border-left-color: #7dd3fc;
        }

        .sidebar-link.active {
            background: rgba(125, 211, 252, 0.15);
            color: #bae6fd;
            border-left-color: #7dd3fc;
        }

        .manager-topbar {
            background: #0f2027;
            border-bottom: 1px solid #203a43;
        }

        .manager-content {
            background: #f0f4f8;
            min-height: 100vh;
        }

        .teal-btn {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            color: white;
            border: none;
            transition: all 0.3s;
        }

        .teal-btn:hover {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            box-shadow: 0 4px 20px rgba(8, 145, 178, 0.4);
            transform: translateY(-1px);
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

<body class="bg-gray-50">

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="manager-sidebar w-64 flex-shrink-0 flex flex-col" id="sidebar">
            {{-- Logo --}}
            <div class="p-6 border-b border-sky-900/30">
                <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-sky-600 flex items-center justify-center">
                        <span class="text-white font-bold text-lg font-playfair">M</span>
                    </div>
                    <div>
                        <p class="text-sky-200 font-playfair font-bold text-sm leading-tight">{{ hotel_name() }}</p>
                        <p class="text-sky-500 text-xs tracking-widest uppercase">Manager Panel</p>
                    </div>
                </a>
            </div>

            {{-- User info --}}
            <div class="p-4 border-b border-sky-900/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-sky-700 flex items-center justify-center flex-shrink-0">
                        <span class="text-sky-200 font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sky-100 font-medium text-sm truncate">{{ Auth::user()->name }}</p>
                        <p class="text-sky-500 text-xs truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-4 overflow-y-auto">
                <p class="px-5 py-2 text-sky-600 text-xs tracking-widest uppercase font-semibold">Menu Manager</p>

                {{-- Dashboard --}}
                <a href="{{ route('manager.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                {{-- Laporan Keuangan --}}
                <a href="{{ route('manager.finance') }}"
                    class="sidebar-link {{ request()->routeIs('manager.finance') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Laporan Keuangan
                </a>

                {{-- Download PDF --}}
                <a href="{{ route('manager.report.pdf') }}"
                    class="sidebar-link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </a>
            </nav>

            {{-- Logout --}}
            <div class="p-4 border-t border-sky-900/30">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="sidebar-link w-full text-left text-red-400 hover:text-red-300 border-l-transparent hover:border-l-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Topbar --}}
            <header class="manager-topbar h-16 flex items-center justify-between px-6 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <h1 class="text-sky-200 font-playfair text-lg font-semibold">
                        @yield('pageTitle', 'Manager Panel')
                    </h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}"
                        class="text-sky-400 hover:text-sky-200 text-sm transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Landing Page
                    </a>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto manager-content p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-4 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error') || session('warning'))
                    <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-5 py-4 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium">{{ session('error') ?? session('warning') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
