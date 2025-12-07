<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HopeFund') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #001a4d 0%, #003d7a 25%, #0066cc 50%, #00a8e8 75%, #00d4ff 100%);
                position: relative;
                overflow: hidden;
            }
            
            .glassmorphism {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 20px;
            }
            
            .glassmorphism-dark {
                background: rgba(30, 41, 59, 0.5);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 20px;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen gradient-bg flex flex-col justify-center items-center p-6">
            <div class="w-full max-w-xl mx-auto">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
