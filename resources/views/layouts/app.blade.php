<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
           
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer style="background-color:#0d6efd; color:white; padding:40px 20px;">
  <div class="footer-container" style="display:flex; flex-wrap:wrap; justify-content:space-between; max-width:1200px; margin:auto; gap:20px;">

    <!-- About / Brand -->
    <div class="footer-section about" style="flex:1; min-width:200px;">
      <h2 style="margin-bottom:10px;">Chiching</h2>
      <p>Helping you save smarter, one step at a time.</p>
    </div>

    <!-- Useful Links -->
    <div class="footer-section links" style="flex:1; min-width:150px;">
      <h3 style="margin-bottom:10px;">Quick Links</h3>
      <ul style="list-style:none; padding:0;">
        <li><a href="#" style="color:white; text-decoration:none;">How it Works</a></li>
        <li><a href="#" style="color:white; text-decoration:none;">FAQ</a></li>
      </ul>
    </div>

    <!-- Legal / Policy -->
    <div class="footer-section legal" style="flex:1; min-width:150px;">
      <h3 style="margin-bottom:10px;">Legal</h3>
      <ul style="list-style:none; padding:0;">
        <li><a href="#" style="color:white; text-decoration:none;">Privacy Policy</a></li>
        <li><a href="#" style="color:white; text-decoration:none;">Terms & Conditions</a></li>
      </ul>
    </div>

  </div>

  <div style="text-align:center; margin-top:30px; font-size:14px; opacity:0.8;">
    &copy; {{ date('Y') }} Chiching. All rights reserved.
  </div>
</footer>


    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset("service-worker.js") }}')
                .then(() => console.log('Service Worker Registered'));
        }
    </script>

</body>

</html>
