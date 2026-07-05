@props(['title' => 'Hotel Eqi'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} | {{ config('app.name', 'Hotel Eqi') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-stone-50 text-slate-800 antialiased">
    <div class="min-h-screen">
        <header class="border-b border-stone-200/80 bg-[#1a1208] text-white shadow-lg shadow-stone-900/10">
            <div class="page-shell flex flex-col gap-4 py-4 lg:flex-row lg:items-center lg:justify-between lg:py-5">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-400">Hotel Eqi</p>
                    <a href="{{ route('user.dashboard') }}"
                        class="font-playfair text-xl font-semibold text-white transition hover:text-amber-300">
                        Hotel Management System
                    </a>
                </div>

                <nav class="flex flex-wrap items-center gap-2 text-sm">
                    @php
                        $navItems = [
                            [
                                'label' => 'Dashboard',
                                'route' => 'user.dashboard',
                                'active' => request()->routeIs('user.dashboard'),
                            ],
                            [
                                'label' => 'Riwayat',
                                'route' => 'user.booking.history',
                                'active' => request()->routeIs('user.booking.history*'),
                            ],
                            ['label' => 'Kamar', 'route' => 'rooms', 'active' => request()->routeIs('rooms')],
                            [
                                'label' => 'Restoran',
                                'route' => 'restaurant',
                                'active' => request()->routeIs('restaurant*'),
                            ],
                            ['label' => 'FAQ', 'route' => 'faq', 'active' => request()->routeIs('faq')],
                            [
                                'label' => 'Profil',
                                'route' => 'profile.edit',
                                'active' => request()->routeIs('profile.edit'),
                            ],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                            class="rounded-full px-3 py-2 font-medium transition {{ $item['active'] ? 'bg-amber-500/20 text-amber-200' : 'text-stone-200 hover:bg-white/10 hover:text-white' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </header>

        <main class="page-shell py-8 lg:py-10">
            @isset($pageTitle)
                <div
                    class="mb-8 flex flex-col gap-4 rounded-3xl border border-stone-200/80 bg-white/80 px-6 py-5 shadow-sm backdrop-blur sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-amber-600">Menu Pengguna</p>
                        <h1 class="font-playfair text-2xl font-semibold text-slate-900">{{ $pageTitle }}</h1>
                    </div>
                    <div class="text-sm text-slate-600">
                        Halo, {{ Auth::user()?->name ?? 'Tamu' }}
                    </div>
                </div>
            @endisset

            {{ $slot }}
        </main>
    </div>
</body>

</html>
