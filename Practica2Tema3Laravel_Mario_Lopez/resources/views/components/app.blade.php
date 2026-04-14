@props(['title' => "Gestió d'alumnes"])

@php
    // ── Load per-user settings (only when authenticated) ──────────────────────
    $appSettings = auth()->check() ? auth()->user()->getOrCreateSettings() : null;

    // Apply language locale for this request
    if ($appSettings) {
        app()->setLocale($appSettings->language ?? 'ca');
    }

    // Build html element classes
    $htmlClasses = 'antialiased';
    if ($appSettings) {
        if ($appSettings->dark_mode)       $htmlClasses .= ' dark-mode';
        if ($appSettings->colorblind_mode) $htmlClasses .= ' colorblind';
        $htmlClasses .= ' font-size-' . ($appSettings->font_size ?? 'medium');
    } else {
        $htmlClasses .= ' font-size-medium';
    }
@endphp

<!doctype html>
<html lang="{{ $appSettings?->language ?? 'ca' }}" class="{{ $htmlClasses }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        primary: {
                            500: '#0066FF',
                            600: '#0052cc'
                        },
                        accent: '#FF4D6D'
                    }
                }
            }
        }
    </script>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root { --card-bg: rgba(255,255,255,0.8); }
        .glass { background: linear-gradient(135deg, rgba(255,255,255,0.75), rgba(255,255,255,0.6)); backdrop-filter: blur(6px); }

        /* ── Font size ───────────────────────────────────────────────────────── */
        html.font-size-small  { font-size: 13px; }
        html.font-size-medium { font-size: 16px; }
        html.font-size-large  { font-size: 19px; }

        /* ── Dark mode ───────────────────────────────────────────────────────── */
        html.dark-mode body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%) !important;
            color: #e2e8f0 !important;
        }
        html.dark-mode .bg-white          { background-color: #1e293b !important; }
        html.dark-mode .bg-gray-50,
        html.dark-mode .bg-slate-50       { background-color: #1a2436 !important; }
        html.dark-mode .bg-gray-100,
        html.dark-mode .bg-slate-100      { background-color: #243046 !important; }
        html.dark-mode .bg-gray-200,
        html.dark-mode .bg-slate-200      { background-color: #2d3f5c !important; }
        html.dark-mode nav.w-full .bg-white { background-color: #1e293b !important; }
        html.dark-mode .rounded-2xl.shadow-md { box-shadow: 0 4px 20px rgba(0,0,0,0.5) !important; }
        html.dark-mode .text-slate-900,
        html.dark-mode .text-gray-900     { color: #f1f5f9 !important; }
        html.dark-mode .text-slate-800,
        html.dark-mode .text-gray-800     { color: #e2e8f0 !important; }
        html.dark-mode .text-slate-700,
        html.dark-mode .text-gray-700     { color: #cbd5e1 !important; }
        html.dark-mode .text-slate-600,
        html.dark-mode .text-gray-600     { color: #94a3b8 !important; }
        html.dark-mode .text-slate-500,
        html.dark-mode .text-gray-500     { color: #64748b !important; }
        html.dark-mode .border-slate-100,
        html.dark-mode .border-gray-100   { border-color: #2d3f5c !important; }
        html.dark-mode .border-slate-200,
        html.dark-mode .border-gray-200   { border-color: #3a506b !important; }
        html.dark-mode .shadow-sm,
        html.dark-mode .shadow-md,
        html.dark-mode .shadow-lg,
        html.dark-mode .shadow-xl         { box-shadow: 0 4px 24px rgba(0,0,0,0.45) !important; }
        html.dark-mode input,
        html.dark-mode select,
        html.dark-mode textarea           {
            background-color: #243046 !important;
            border-color: #3a506b !important;
            color: #e2e8f0 !important;
        }
        html.dark-mode input::placeholder { color: #64748b !important; }
        html.dark-mode .bg-blue-50        { background-color: #1e3a5f !important; }
        html.dark-mode .bg-green-50       { background-color: #14532d50 !important; }
        html.dark-mode .bg-red-50         { background-color: #450a0a50 !important; }
        html.dark-mode .bg-yellow-100     { background-color: #42300a !important; }
        html.dark-mode .text-yellow-700   { color: #fcd34d !important; }
        html.dark-mode footer             { color: #64748b !important; }
        html.dark-mode .hover\:bg-blue-50:hover   { background-color: #1e3a5f !important; }
        html.dark-mode .hover\:bg-gray-50:hover,
        html.dark-mode .hover\:bg-slate-50:hover  { background-color: #243046 !important; }
        html.dark-mode article.border     { border-color: #2d3f5c !important; }
        html.dark-mode .bg-gray-100.min-h-screen  { background-color: #0f172a !important; }

        /* ── Colorblind mode (deuteranopia-friendly) ─────────────────────────── */
        html.colorblind body {
            /* Shift hues so red/green differences become visible as brightness diffs */
            filter: url('#colorblind-svg-filter');
        }
        /* Fallback for browsers that don't support SVG filters in CSS */
        @supports not (filter: url('#x')) {
            html.colorblind body { filter: saturate(1.4) contrast(1.05); }
        }
    </style>

    {{-- Inline SVG filter for deuteranopia correction --}}
    <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;width:0;height:0;overflow:hidden" aria-hidden="true">
        <defs>
            <filter id="colorblind-svg-filter" color-interpolation-filters="linearRGB">
                <feColorMatrix type="matrix" values="
                    0.625 0.375 0     0 0
                    0.7   0.3   0     0 0
                    0     0.3   0.7   0 0
                    0     0     0     1 0"/>
            </filter>
        </defs>
    </svg>
</head>

<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-pink-50 to-yellow-50 text-slate-800 dark:bg-slate-900 dark:text-slate-100">
    
    <x-navbar />
    <main class="w-full pt-16 md:pt-20 pb-8">
        {{ $slot }}
    </main>

    <!-- small footer placeholder -->
    <footer class="text-center text-sm text-slate-600 dark:text-slate-400 py-8">
        &copy; {{ date('Y') }} - Gestió d'alumnes
    </footer>

    {{-- ─── Logout farewell overlay ───────────────────────────────────── --}}
    <div id="logout-overlay"
         class="fixed inset-0 z-[9999] flex flex-col items-center justify-center gap-8 pointer-events-none"
         style="background: linear-gradient(135deg, #eef2ff 0%, #fdf2f8 50%, #fefce8 100%);
                opacity: 0; transition: opacity 0.35s ease;">

        {{-- Logo --}}
        <div class="flex flex-col items-center gap-3 select-none">
            <div class="h-16 w-16 rounded-2xl flex items-center justify-center shadow-lg"
                 style="background: linear-gradient(135deg, #0066FF, #FF4D6D);">
                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="text-2xl font-extrabold tracking-tight"
                  style="background: linear-gradient(135deg, #0066FF, #FF4D6D);
                         -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                JobsMerce
            </span>
        </div>

        {{-- Farewell text --}}
        <div class="flex flex-col items-center gap-1 text-center">
            <p class="text-xl font-bold text-slate-800">Fins aviat! 👋</p>
            <p class="text-sm text-slate-400">Tancant la sessió…</p>
        </div>

        {{-- Progress bar --}}
        <div class="w-64 flex flex-col gap-2">
            <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                <div id="logout-bar"
                     class="h-full rounded-full"
                     style="width: 0%; background: linear-gradient(90deg, #0066FF, #FF4D6D); transition: none;">
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            // Intercept every logout form (action contains "logout")
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!form || !form.action || !form.action.includes('logout')) return;

                e.preventDefault();

                const overlay = document.getElementById('logout-overlay');
                const bar     = document.getElementById('logout-bar');
                if (!overlay || !bar) { form.submit(); return; }

                // Show overlay
                overlay.style.pointerEvents = 'all';
                overlay.style.opacity       = '1';

                let progress = 0;
                const totalMs = 1400;
                const steps   = 70;
                const delay   = totalMs / steps;

                const tick = setInterval(function () {
                    if (progress < 80) {
                        progress += (80 - progress) * 0.07 + 0.5;
                    } else {
                        progress += 0.35;
                    }

                    bar.style.transition = 'width 0.1s linear';
                    bar.style.width      = Math.min(progress, 100) + '%';

                    if (progress >= 100) {
                        clearInterval(tick);
                        bar.style.transition = 'width 0.2s ease';
                        bar.style.width      = '100%';
                        setTimeout(function () { form.submit(); }, 200);
                    }
                }, delay);
            });
        })();
    </script>
</body>
</html>