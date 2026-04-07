@props(['title' => "Gestió d'alumnes"])

<!doctype html>
<html lang="ca" class="antialiased">
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
        :root{--card-bg:rgba(255,255,255,0.8);} 
        .glass { background: linear-gradient(135deg, rgba(255,255,255,0.75), rgba(255,255,255,0.6)); backdrop-filter: blur(6px); }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-pink-50 to-yellow-50 text-slate-800 dark:bg-slate-900 dark:text-slate-100">
    
    <x-navbar />
    <main class="container mx-auto px-4 pt-16 md:pt-20 pb-8">
        
        {{ $slot }}
    </main>

    <!-- small footer placeholder -->
    <footer class="text-center text-sm text-slate-600 dark:text-slate-400 py-8">
        &copy; {{ date('Y') }} - Gestió d'alumnes
    </footer>
</body>
</html>