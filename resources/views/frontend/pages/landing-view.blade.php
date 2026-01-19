<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landing_page->title ?? 'কম্বো প্যাকেজ' }}</title>
    <x-favicon-icon />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-bg: {{ $common['primary_bg_color'] ?? '#dc2626' }};
            --primary-text: {{ $common['primary_text_color'] ?? '#ffffff' }};
            --secondary-bg: {{ $common['secondary_bg_color'] ?? '#fbbf24' }};
            --secondary-text: {{ $common['secondary_text_color'] ?? '#000000' }};
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-bg) #f3f4f6;
        }

        .dashed-border {
            border-style: dashed;
            border-width: 3px;
            animation: dashMove 20s linear infinite;
        }

        @keyframes dashMove {
            0% {
                stroke-dashoffset: 0;
            }

            100% {
                stroke-dashoffset: 100%;
            }
        }

        .bg-gradient-red {
            background: linear-gradient(135deg, var(--primary-bg) 0%, var(--primary-bg) 50%, var(--primary-bg) 100%);
            position: relative;
            overflow: hidden;
        }

        .bg-gradient-red::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: shine 3s ease-in-out infinite;
        }

        @keyframes shine {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(20px, 20px);
            }
        }

        .checkmark {
            color: var(--primary-bg);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse-scale {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px var(--primary-bg);
            }

            50% {
                box-shadow: 0 0 40px var(--primary-bg);
            }
        }

        .animate-slide-in-up {
            animation: slideInUp 0.6s ease-out;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 50px color-mix(in srgb, var(--primary-bg) 30%, transparent);
        }

        .btn-glow {
            position: relative;
            overflow: hidden;
        }

        .btn-glow::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-glow:hover::after {
            left: 100%;
        }

        .section-divider {
            position: relative;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-bg), transparent);
            margin: 3rem 0;
        }

        .badge-premium {
            position: absolute;
            top: -15px;
            right: 20px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 0.875rem;
            transform: rotate(15deg);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary-bg), var(--primary-bg));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-item {
            transition: all 0.3s ease;
            position: relative;
            padding-left: 0;
        }

        .feature-item:hover {
            padding-left: 10px;
            transform: scale(1.02);
        }

        .feature-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, var(--primary-bg), var(--secondary-bg));
            border-radius: 2px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-item:hover::before {
            opacity: 1;
        }

        .image-overlay {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
        }

        .image-overlay::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-bg), var(--secondary-bg));
            opacity: 0.1;
            transition: opacity 0.3s ease;
        }

        .image-overlay:hover::after {
            opacity: 1;
        }

        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }

        .stagger-4 {
            animation-delay: 0.4s;
        }

        .stagger-5 {
            animation-delay: 0.5s;
        }

        .stagger-6 {
            animation-delay: 0.6s;
        }

        /* Input focus states */
        input:focus,
        textarea:focus {
            border-color: var(--primary-bg) !important;
            --tw-ring-color: var(--primary-bg) !important;
        }

        input:hover,
        textarea:hover {
            border-color: var(--primary-bg) !important;
        }

        /* Package hover color */
        .packages-hover:hover {
            color: var(--primary-bg) !important;
        }
    </style>
</head>

<body class="bg-gray-50">

    @forelse($sections as $section)
        @switch($section['type'])
            @case('hero')
                @include('components.landing-sections.hero-section', [
                    'section' => $section,
                    'common' => $common,
                ])
            @break

            @case('packages')
                @include('components.landing-sections.packages-section', [
                    'section' => $section,
                    'common' => $common,
                ])
            @break

            @case('features')
                @include('components.landing-sections.features-section', [
                    'section' => $section,
                    'common' => $common,
                ])
            @break

            @case('testimonials')
                @include('components.landing-sections.testimonials-section', [
                    'section' => $section,
                    'common' => $common,
                ])
            @break

            @case('checkout')
                @include('components.landing-sections.checkout-section', [
                    'section' => $section,
                    'common' => $common,
                    'landing_page' => $landing_page,
                ])
            @break
        @endswitch
        @empty
            <div class="text-center py-20">
                <p class="text-gray-500 text-lg">কোনো সেকশন কনফিগার করা হয়নি।</p>
            </div>
        @endforelse

        @include('components.landing-sections.footer-section')

    </body>

    </html>
