<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendex</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Lucide Icons -->
     <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
    * {
        font-family: 'Inter', sans-serif;
    }

    /* Custom Animations */
    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes pulse-glow {

        0%,
        100% {
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
        }

        50% {
            box-shadow: 0 0 40px rgba(168, 85, 247, 0.6);
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes morph {

        0%,
        100% {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }

        50% {
            border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
        }
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

    @keyframes rotate-slow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes bounce-in {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }

        50% {
            transform: scale(1.05);
        }

        70% {
            transform: scale(0.9);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }

        100% {
            background-position: 1000px 0;
        }
    }

    @keyframes wiggle {

        0%,
        7% {
            transform: rotateZ(0);
        }

        15% {
            transform: rotateZ(-15deg);
        }

        20% {
            transform: rotateZ(10deg);
        }

        25% {
            transform: rotateZ(-10deg);
        }

        30% {
            transform: rotateZ(6deg);
        }

        35% {
            transform: rotateZ(-4deg);
        }

        40%,
        100% {
            transform: rotateZ(0);
        }
    }

    @keyframes wave {

        0%,
        100% {
            transform: rotate(-3deg);
        }

        50% {
            transform: rotate(3deg);
        }
    }

    @keyframes typing {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }

    @keyframes blink {

        0%,
        50% {
            border-color: transparent;
        }

        51%,
        100% {
            border-color: white;
        }
    }

    .animate-gradient {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    .animate-slide-up {
        animation: slide-up 0.8s ease-out forwards;
    }

    .animate-morph {
        animation: morph 8s ease-in-out infinite;
    }

    .animate-particle-float {
        animation: particle-float 15s linear infinite;
    }

    .animate-rotate-slow {
        animation: rotate-slow 20s linear infinite;
    }

    .animate-bounce-in {
        animation: bounce-in 0.8s ease-out;
    }

    .animate-shimmer {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }

    .animate-wiggle {
        animation: wiggle 2s ease-in-out infinite;
    }

    .animate-wave {
        animation: wave 1s ease-in-out infinite;
    }

    .gradient-text {
        background: linear-gradient(135deg, #10F0B2 0%, #17926fff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .gradient-text-2 {
        background: linear-gradient(135deg, #17926fff 0%, #10F0B2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .hover-scale {
        transition: all 0.3s ease;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }

    .hover-rotate {
        transition: all 0.3s ease;
    }

    .hover-rotate:hover {
        transform: rotate(360deg);
    }

    .scroll-smooth {
        scroll-behavior: smooth;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    ::selection {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .particle {
        position: fixed;
        pointer-events: none;
        opacity: 0.3;
        z-index: 1;
    }

    .morph-shape {
        position: fixed;
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        filter: blur(40px);
        pointer-events: none;
        z-index: 0;
    }

    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .stagger-1 {
        transition-delay: 0.1s;
    }

    .stagger-2 {
        transition-delay: 0.2s;
    }

    .stagger-3 {
        transition-delay: 0.3s;
    }

    .stagger-4 {
        transition-delay: 0.4s;
    }

    .stagger-5 {
        transition-delay: 0.5s;
    }

    .stagger-6 {
        transition-delay: 0.6s;
    }

    .typing-effect {
        overflow: hidden;
        border-right: 3px solid white;
        white-space: nowrap;
        animation: typing 3s steps(30, end), blink 0.75s step-end infinite;
    }

    .parallax-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-size: 400% 400%;
        animation: gradient 20s ease infinite;
    }

    .card-gradient-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-gradient-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .card-gradient-3 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .card-gradient-4 {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .card-gradient-5 {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .card-gradient-6 {
        background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
    }
    </style>
</head>

<body class="bg-gray-50 scroll-smooth custom-scrollbar">

    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <!-- Floating Particles -->
        <div class="particle w-4 h-4 bg-purple-400 rounded-full animate-particle-float"
            style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle w-6 h-6 bg-blue-400 rounded-full animate-particle-float"
            style="left: 20%; animation-delay: 2s;"></div>
        <div class="particle w-3 h-3 bg-pink-400 rounded-full animate-particle-float"
            style="left: 30%; animation-delay: 4s;"></div>
        <div class="particle w-5 h-5 bg-indigo-400 rounded-full animate-particle-float"
            style="left: 40%; animation-delay: 6s;"></div>
        <div class="particle w-4 h-4 bg-purple-400 rounded-full animate-particle-float"
            style="left: 50%; animation-delay: 8s;"></div>
        <div class="particle w-6 h-6 bg-blue-400 rounded-full animate-particle-float"
            style="left: 60%; animation-delay: 10s;"></div>
        <div class="particle w-3 h-3 bg-pink-400 rounded-full animate-particle-float"
            style="left: 70%; animation-delay: 12s;"></div>
        <div class="particle w-5 h-5 bg-indigo-400 rounded-full animate-particle-float"
            style="left: 80%; animation-delay: 14s;"></div>
        <div class="particle w-4 h-4 bg-purple-400 rounded-full animate-particle-float"
            style="left: 90%; animation-delay: 16s;"></div>

        <!-- Morphing Shapes -->
        <div class="morph-shape w-96 h-96 animate-morph animate-float" style="top: 10%; left: 10%;"></div>
        <div class="morph-shape w-80 h-80 animate-morph animate-float"
            style="top: 60%; right: 10%; animation-delay: 2s;"></div>
        <div class="morph-shape w-64 h-64 animate-morph animate-float"
            style="bottom: 20%; left: 50%; animation-delay: 4s;"></div>
    </div>

    <!-- Header -->
    <header class="fixed top-0 w-full z-50 glass-effect transition-all duration-300" id="header">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <x-application-vector class="w-10 h-10"></x-application-vector>
                    <span class="text-2xl font-bold ">Vendex</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-purple-600 transition-colors">Features</a>
                    <a href="#tracking" class="text-gray-700 hover:text-purple-600 transition-colors">Tracking</a>
                    <a href="#testimonials"
                        class="text-gray-700 hover:text-purple-600 transition-colors">Testimonials</a>
                    <a href="#contact" class="text-gray-700 hover:text-purple-600 transition-colors">Contact</a>
                </div>

                <div class="flex items-center space-x-4">
                      @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="/agent/dashboard"
                        class="px-6 py-2 bg-gray-900  text-white rounded-full hover:shadow-lg transition-all hover-scale">
                       dashboard
                    </a>
                            
                      
                    @else
                       <a href="/login" class="px-4 py-2 text-gray-900 hover:text-green-700 transition-colors">Sign In</a>
                    <a href="/register"
                        class="px-6 py-2 bg-gray-900  text-white rounded-full hover:shadow-lg transition-all hover-scale">
                        Get Started
                    </a>
                     
                    @endauth
                </nav>
            @endif
                    
                </div>

                <button class="md:hidden" id="mobile-menu-btn">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex flex-col lg:flex-row items-center justify-center lg:justify-between overflow-hidden bg-[#fafafa] pt-32 pb-12 px-6 lg:px-20">

    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[300px] h-[300px] md:w-[500px] md:h-[500px] bg-purple-100/50 rounded-full blur-[80px] md:blur-[120px] opacity-60"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[250px] h-[250px] md:w-[400px] md:h-[400px] bg-blue-100/50 rounded-full blur-[70px] md:blur-[100px] opacity-60"></div>

    <div class="w-full lg:w-[45%] relative z-20 mb-16 lg:mb-0 text-center lg:text-left">
        <div class="animate__animated animate__fadeInUp">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-slate-100 shadow-sm mb-6">
                <span class="flex h-2 w-2">
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-600"></span>
                </span>
                <span class="text-[10px] md:text-[11px] font-bold text-slate-500 uppercase tracking-widest">the best orders confirmation service</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-[74px] font-black leading-[1.1] lg:leading-[0.9] tracking-tighter text-slate-900 mb-8">
                Just One Click <br>
                <span class="text-transparent text-3xl sm:text-4xl lg:text-[50px] bg-clip-text bg-gradient-to-r from-gray-600 to-[#10F0B2]">
                    We Confirme it Quick.
                </span>
            </h1>

            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto lg:mx-0 leading-relaxed mb-10 font-medium block">
                Reduce <span class="text-slate-900 font-bold  decoration-4  ">Cancelled Orders</span>, and boost your confirmation rate with
                <span class="inline-flex items-baseline gap-1.5 font-extrabold text-slate-950">
                    <span class="w-2 h-2 bg-[#10F0B2] rounded-full animate-pulse"></span>
                    VENDEX
                </span>,
                the fastest confirmation service
            </p>

            <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 mb-12">
                <a href="/register"
                    class="px-10 py-5 bg-slate-900 text-white rounded-2xl font-bold text-lg hover:bg-purple-600 transition-all shadow-xl hover:-translate-y-1 text-center">
                    Start With Us
                </a>
            </div>

            <div class="flex items-center justify-center lg:justify-start gap-4 py-6 border-t border-slate-100">
                <div class="flex -space-x-2">
                    <div class="w-8 h-8 rounded-full bg-[#10F0B220] border-2 border-white"></div>
                    <div class="w-8 h-8 rounded-full bg-[#10F0B260] border-2 border-white"></div>
                    <div class="w-8 h-8 rounded-full bg-[#10F0B280] border-2 border-white"></div>
                </div>
                <p class="text-xs md:text-sm font-bold text-slate-400 uppercase tracking-tighter">Trusted by +70 Stores</p>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-[55%] relative flex justify-center items-center perspective-1000">
        <div class="relative w-full max-w-[320px] sm:max-w-xl lg:max-w-3xl transform scale-90 sm:scale-100 rotate-x-6 rotate-y-[-12deg] rotate-z-3 hover:rotate-0 transition-all duration-1000 ease-out animate__animated animate__zoomIn">

            <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] border border-slate-200 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] md:shadow-[0_50px_100px_-20px_rgba(0,0,0,0.12)] overflow-hidden">
                <div class="h-10 md:h-12 bg-slate-50/50 backdrop-blur-md border-b border-slate-100 flex items-center px-4 md:px-8 justify-between">
                    <div class="flex gap-1.5">
                        <div class="w-2 md:w-3 h-2 md:h-3 bg-red-400/20 rounded-full border border-red-400/40"></div>
                        <div class="w-2 md:w-3 h-2 md:h-3 bg-amber-400/20 rounded-full border border-amber-400/40"></div>
                        <div class="w-2 md:w-3 h-2 md:h-3 bg-green-400/20 rounded-full border border-green-400/40"></div>
                    </div>
                    <div class="text-[8px] md:text-[10px] font-bold text-slate-300 tracking-widest uppercase">www.vendexdz.com</div>
                    <div class="w-6 md:w-10"></div>
                </div>

                <div class="p-4 md:p-8 bg-white min-h-fit">
                    <div class="grid grid-cols-12 gap-3 md:gap-6">
                        <div class="col-span-1 space-y-4 md:space-y-6">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-purple-100 rounded-lg"></div>
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-slate-50 rounded-lg"></div>
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-slate-50 rounded-lg"></div>
                        </div>

                        <div class="col-span-11">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 md:gap-4 mb-6 md:mb-10">
                                <div class="p-3 md:p-5 bg-slate-50 rounded-xl md:rounded-2xl border border-slate-100">
                                    <div class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase mb-1">Return</div>
                                    <div class="text-sm md:text-2xl font-black text-red-500">13.7%</div>
                                </div>
                                <div class="p-3 md:p-5 bg-gray-900 rounded-xl md:rounded-2xl text-white shadow-xl">
                                    <div class="text-[8px] md:text-[10px] font-bold opacity-80 text-white uppercase mb-1">Confirmed</div>
                                    <div class="text-sm md:text-2xl font-black text-white tracking-tighter">84.9%</div>
                                </div>
                                <div class="p-3 md:p-5 bg-slate-50 rounded-xl md:rounded-2xl border border-slate-100 col-span-2 sm:col-span-1">
                                    <div class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase mb-1">benefits</div>
                                    <div class="text-sm md:text-2xl font-black">+120k DZD</div>
                                </div>
                            </div>
                            <div class="h-24 md:h-40 w-full bg-slate-50 rounded-2xl md:rounded-3xl border border-slate-100 flex items-end justify-between p-3 md:p-6 gap-1 md:gap-2">
                                <div class="w-full bg-[#10F0B270] h-1/2 rounded-md"></div>
                                <div class="w-full bg-[#10F0B290] h-3/4 rounded-md"></div>
                                <div class="w-full bg-[#10F0B2] h-full rounded-md animate-pulse"></div>
                                <div class="w-full bg-[#10F0B295] h-2/3 rounded-md"></div>
                                <div class="w-full bg-[#10F0B280] h-1/3 rounded-md"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute -right-2 -bottom-2 md:-right-6 md:-bottom-6 bg-slate-900 text-white p-3 md:p-5 rounded-xl md:rounded-3xl shadow-2xl animate__animated animate__fadeInUp animate__delay-1s">
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="relative flex h-2 md:h-3 w-2 md:w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 md:h-3 w-2 md:w-3 bg-green-500"></span>
                    </div>
                    <div>
                        <p class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">Real-time Feed</p>
                        <p class="text-[9px] md:text-xs font-bold whitespace-nowrap">New order is confirmed <span class="text-[#10F0B2]">Now!</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .perspective-1000 {
            perspective: 1200px;
        }
        /* Mobile adjustment for 3D rotation to keep it centered */
        @media (max-width: 1023px) {
            .perspective-1000 {
                perspective: none;
                margin-top: 2rem;
            }
            .rotate-x-6 {
                transform: rotateX(0) rotateY(0) rotateZ(0) !important;
            }
        }
        @media (min-width: 1024px) {
            .rotate-x-6 {
                transform: rotateX(8deg) rotateY(-15deg) rotateZ(2deg);
            }
        }
    </style>
</section>

    <!-- Features Section -->
    <section id="features" class="py-20 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="gradient-text">Our Services</span>
                </h2>
                <p class="text-xl text-gray-600">Everything you need to manage order confirmations efficiently</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-1 border border-white/20">
                    <div class="w-16 h-16 card-gradient-1 rounded-xl flex items-center justify-center mb-6 hover-scale">
                        <i  class="ri-flashlight-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Fast Confirmation</h3>
                   
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-2 border border-white/20">
                    <div class="w-16 h-16 card-gradient-2 rounded-xl flex items-center justify-center mb-6 hover-scale">
                        <i  class="ri-compass-3-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Instant Tracking</h3>
                    <!--<p class="text-gray-600">Track confirmation rates, delivery times, and customer satisfaction with
                        comprehensive analytics dashboard.</p>-->
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-3 border border-white/20">
                    <div class="w-16 h-16 card-gradient-3 rounded-xl flex items-center justify-center mb-6 hover-scale">
                        <i  class="ri-line-chart-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Accurate Statistics</h3>
                    
                </div>

                <!-- Feature 4 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-4 border border-white/20">
                    <div class="w-16 h-16 card-gradient-4 rounded-xl flex items-center justify-center mb-6 hover-scale">
                         <i  class="ri-file-chart-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Daily Reports</h3>
                    
                </div>

                <!-- Feature 5 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-5 border border-white/20">
                    <div class="w-16 h-16 card-gradient-5 rounded-xl flex items-center justify-center mb-6 hover-scale">
                         <i  class="ri-truck-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">High  Delivery Rate</h3>
                    
                </div>

                <!-- Feature 6 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-6 border border-white/20">
                    <div class="w-16 h-16 card-gradient-6 rounded-xl flex items-center justify-center mb-6 hover-scale">
                         <i  class="ri-customer-service-2-fill text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">High Confirmation Rate</h3>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Order Tracking Demo -->
    <section id="tracking" class="py-20 bg-gradient-to-br from-purple-50 to-pink-50 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="gradient-text">Real-Time Order Tracking</span>
                </h2>
                <p class="text-xl text-gray-600">We give you a complete visibilty to track your orders</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-8 animate-on-scroll border border-white/20">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-semibold mb-2">Order #ORD-Vendex-007</h3>
                            <p class="text-gray-600">Placed on january 1, 2026 at 10:30 AM</p>
                        </div>
                        <div class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold animate-pulse">
                            In Transit
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="relative">
                        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-green-500 to-blue-500">
                        </div>

                        <!-- Step 1 -->
                        <div class="relative flex items-center mb-8 animate-on-scroll stagger-1">
                            <div
                                class="w-16 h-16 bg-gray-500 rounded-full flex items-center justify-center z-10 animate-bounce-in">
                                <i data-lucide="package" class="w-8 h-8 text-white"></i>
                            </div>
                            <div class="ml-6">
                                <h4 class="text-lg font-semibold">Order Created</h4>
                                <p class="text-gray-600">january 1, 2026 at 10:30 AM</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative flex items-center mb-8 animate-on-scroll stagger-2">
                            <div
                                class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center z-10 animate-bounce-in">
                                <i data-lucide="check" class="w-8 h-8 text-white"></i>
                            </div>
                            
                            <div class="ml-6">
                                <h4 class="text-lg font-semibold">Confirmed</h4>
                                <p class="text-gray-600">january 1, 2026 at 10:40 AM</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative flex items-center mb-8 animate-on-scroll stagger-3">
                            <div
                                class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center z-10 animate-pulse">
                                <i data-lucide="truck" class="w-8 h-8 text-white"></i>
                            </div>
                            <div class="ml-6">
                                <h4 class="text-lg font-semibold">In transit</h4>
                                <p class="text-gray-600">january 1, 2026 at 01:00 PM</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="relative flex items-center animate-on-scroll stagger-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center z-10">
                                <i data-lucide="home" class="w-8 h-8 text-white"></i>
                            </div>
                            <div class="ml-6">
                                <h4 class="text-lg font-semibold text-gray-400">Delivered</h4>
                                 <p class="text-gray-600">january 2, 2026 at 11:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-20 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="gradient-text">What Our Sellers Said</span>
                </h2>
                <p class="text-xl text-gray-600">Be one of them</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-1 border border-white/20">
                    <div class="flex items-center mb-4 ">
                        <span  class="p-2 w-12 h-12 bg-green-600 rounded-full mr-4">
                            <i class="ri-user-follow-line text-3xl text-white"></i>
                        </span>
                        <div>
                            <h4 class="font-semibold">Yacine</h4>
                            
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.1s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.2s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.3s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.4s;"></i>
                    </div>
                    <p class="text-gray-600 text-right">"خدمتكم نقية ما شاء الله .وملي خدمت معاكم زادتلي نسبة التوصيل صراحة مكنتش متوقع هذا التغيير"</p>
                </div>

                <!-- Testimonial 2 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-2 border border-white/20">
                    <div class="flex items-center mb-4">
                        <span  class="p-2 w-12 h-12 bg-purple-600 rounded-full mr-4">
                            <i class="ri-user-follow-line text-3xl text-white"></i>
                        </span>
                        <div>
                            <h4 class="font-semibold">Khalil</h4>
                            
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.1s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.2s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.3s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.4s;"></i>
                    </div>
                    <p class="text-gray-600 text-right">"السارفيس تاعكم هايل زادتلي نسبة التأكيد ب 15% خدمت مع سارفيس اخرى من قبل دخلوني في حيط بصح معاكم لقيت روحي"</p>
                </div>

                <!-- Testimonial 3 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 hover-lift animate-on-scroll stagger-3 border border-white/20">
                    <div class="flex items-center mb-4">
                        <span  class="p-2 w-12 h-12 bg-yellow-300 rounded-full mr-4">
                            <i class="ri-user-follow-line text-3xl text-white"></i>
                        </span>
                        <div>
                            <h4 class="font-semibold">Youcef</h4>
                        
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.1s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.2s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.3s;"></i>
                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current animate-pulse"
                            style="animation-delay: 0.4s;"></i>
                    </div>
                    <p class="text-gray-600 text-right">"يعطيكم الصحة خدمتكم هايلة ربي يبارك شكرا بزاف راكم ديما فالمستوى"</p>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Contact Section -->
    <section id="contact" class="py-20 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="gradient-text">Our Contact</span>
                </h2>
                <p class="text-xl text-gray-600">We're here to help you succeed</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Contact Card 1 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 text-center hover-lift animate-on-scroll stagger-1 border border-white/20">
                    <div
                        class="w-16 h-16 card-gradient-1 rounded-xl flex items-center justify-center mx-auto mb-6 hover-scale">
                        <i data-lucide="mail" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Email Us</h3>
                    <p class="text-gray-600">vendexentreprise@gmail.com</p>

                </div>

                <!-- Contact Card 2 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 text-center hover-lift animate-on-scroll stagger-2 border border-white/20">
                    <div
                        class="w-16 h-16 card-gradient-3 rounded-xl flex items-center justify-center mx-auto mb-6 hover-scale">
                        <i data-lucide="phone" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Call Us</h3>
                    <p class="text-gray-600">+213 672764767</p>

                </div>

                <!-- Contact Card 3 -->
                <div
                    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 text-center hover-lift animate-on-scroll stagger-3 border border-white/20">
                    <div
                        class="w-16 h-16 card-gradient-2 rounded-xl flex items-center justify-center mx-auto mb-6 hover-scale">
                        <i data-lucide="map-pin" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Visit Us</h3>
                    <p class="text-gray-600">Algiers, bordj el kiffan</p>
                </div>
            </div>            
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                       <x-application-vector class="w-10 h-10"></x-application-vector>
                        <span class="text-xl font-bold">Vendex</span>
                    </div>
                    <p class="text-gray-400">Reduce Cancelled Orders,and boost your confirmation rate with 
VENDEX, the fastest confirmation service.</p>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">© 2026 Vendex. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors hover-scale">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors hover-scale">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors hover-scale">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors hover-scale">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.createElement('div');
    mobileMenu.className =
        'hidden md:hidden bg-white/80 backdrop-blur-lg shadow-lg rounded-lg mt-4 p-4 border border-white/20';
    mobileMenu.innerHTML = `
            <a href="#features" class="block py-2 text-gray-700 hover:text-purple-600">Features</a>
            <a href="#tracking" class="block py-2 text-gray-700 hover:text-purple-600">Tracking</a>
            <a href="#testimonials" class="block py-2 text-gray-700 hover:text-purple-600">Testimonials</a>
            <a href="#contact" class="block py-2 text-gray-700 hover:text-purple-600">Contact</a>
        `;

    mobileMenuBtn.parentNode.appendChild(mobileMenu);

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Header scroll effect
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('bg-white/95', 'shadow-lg');
            header.classList.remove('bg-transparent');
        } else {
            header.classList.add('bg-transparent');
            header.classList.remove('bg-white/95', 'shadow-lg');
        }
    });

    // Scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form submissions (prevent default for demo)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            alert(
                'Form submitted! (This is a demo - in production, this would send data to your server)');
        });
    });

    // Add interactive particle effects on mouse move
    document.addEventListener('mousemove', (e) => {
        const particles = document.querySelectorAll('.particle');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;

        particles.forEach((particle, index) => {
            const speed = (index + 1) * 0.01;
            const x = (mouseX - 0.5) * speed * 100;
            const y = (mouseY - 0.5) * speed * 100;
            particle.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    // Add parallax effect to morphing shapes
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const shapes = document.querySelectorAll('.morph-shape');

        shapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.5;
            const yPos = -(scrolled * speed);
            shape.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Add dynamic typing effect to hero title
    const heroTitle = document.querySelector('h1');
    const words = ['Streamline', 'Transform', 'Elevate', 'Automate'];
    let wordIndex = 0;

    function changeWord() {
        const spans = heroTitle.querySelectorAll('span');
        if (spans.length > 0) {
            wordIndex = (wordIndex + 1) % words.length;
            spans[0].textContent = words[wordIndex] + ' Your';
        }
    }

    // Uncomment to enable dynamic word changing
    // setInterval(changeWord, 3000);

    // Add loading animation
    window.addEventListener('load', () => {
        document.body.classList.add('loaded');
    });
    </script>
</body>

</html>