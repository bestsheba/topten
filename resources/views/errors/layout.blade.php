<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary: #4F46E5;
            --secondary: #06B6D4;
            --text-color: #1F2937;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .error-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .glow-effect {
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: particle-float 8s infinite linear;
        }

        @keyframes particle-float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Background Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="particle" style="left: 10%; animation-delay: 0s; width: 4px; height: 4px;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s; width: 6px; height: 6px;"></div>
        <div class="particle" style="left: 35%; animation-delay: 4s; width: 3px; height: 3px;"></div>
        <div class="particle" style="left: 50%; animation-delay: 6s; width: 5px; height: 5px;"></div>
        <div class="particle" style="left: 70%; animation-delay: 8s; width: 4px; height: 4px;"></div>
        <div class="particle" style="left: 85%; animation-delay: 10s; width: 6px; height: 6px;"></div>
        <div class="particle" style="left: 95%; animation-delay: 12s; width: 3px; height: 3px;"></div>
    </div>

    <div class="error-container rounded-3xl p-8 md:p-12 max-w-2xl w-full text-center relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>

        <!-- Error Icon -->
        <div class="mb-8">
            @yield('icon')
        </div>

        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-8xl md:text-9xl font-bold error-icon floating-animation">
                @yield('code')
            </h1>
        </div>

        <!-- Error Title -->
        <div class="mb-4">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                @yield('title')
            </h2>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <p class="text-lg text-gray-600 max-w-md mx-auto leading-relaxed">
                @yield('message')
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ url()->previous() }}" class="btn-primary text-white px-8 py-3 rounded-full font-semibold inline-flex items-center gap-2 glow-effect">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
            <a href="{{ route('index') }}" class="glass-effect text-gray-700 px-8 py-3 rounded-full font-semibold inline-flex items-center gap-2 hover:bg-white hover:shadow-lg transition-all duration-300">
                <i class="fas fa-home"></i>
                Home Page
            </a>
        </div>

        <!-- Additional Help -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Need help? <a href="mailto:support@{{ parse_url(config('app.url'), PHP_URL_HOST) }}" class="text-blue-600 hover:text-blue-800 font-medium">Contact Support</a>
            </p>
        </div>
    </div>
</body>

</html>
