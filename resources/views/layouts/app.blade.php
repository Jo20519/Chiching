<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chiching</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="theme-color" content="#0d6efd">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
            <img src="{{ asset('images/icons/web-app-manifest-192x192.png') }}" alt="Chiching Logo" width="150">
               
            </div>
            <ul class="nav-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
                <li><a href="{{ url('/groups') }}">Groups</a></li>
                <li><a href="{{ url('/transactions') }}">Transactions</a></li>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset("service-worker.js") }}')
                .then(() => console.log('Service Worker Registered'));
        }
    </script>
</body>
</html>
