@props(['title' => "Gestió d'alumnes"])

<!doctype html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>

<body style="font-family: Open Sans, sans-serif; background-color: rgb(134, 134, 134)">
    <x-navbar>
        
    </x-navbar>
    <section class="px-6 py-8">


        {{ $slot }}

    </section>
</body>
</html>