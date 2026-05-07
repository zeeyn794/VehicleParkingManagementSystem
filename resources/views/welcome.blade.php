<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ParkMaster') }} - Professional Parking Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(245, 48, 3, 0.1);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .glass-nav {
            background: rgba(10, 10, 10, 0.7);
        }
        .gradient-text {
            background: linear-gradient(135deg, #f53003 0%, #ff6b4a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans antialiased">
    
    <header class="w-full p-6 lg:px-20 flex justify-between items-center glass-nav sticky top-0 z-50 border-b border-[#19140010] dark:border-[#ffffff10]">
        <div class="text-2xl font-bold tracking-tighter">
            <span class="text-[#f53003]">Park</span>Master
        </div>

        <nav class="hidden md:flex items-center gap-8">
            <a href="#features" class="text-sm font-medium hover:text-[#f53003] transition-colors">Features</a>
            <a href="#how-it-works" class="text-sm font-medium hover:text-[#f53003] transition-colors">How it Works</a>
            <a href="#about" class="text-sm font-medium hover:text-[#f53003] transition-colors">About Us</a>
            <a href="#contact" class="text-sm font-medium hover:text-[#f53003] transition-colors">Contact Us</a>
        </nav>

        <nav class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 border border-[#19140035] dark:border-[#3E3E3A] rounded-full text-sm font-medium hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium hover:text-[#f53003] transition-colors">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-[#f53003] text-white rounded-full text-sm font-bold shadow-lg shadow-[#f5300330] hover:scale-105 transition-all">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </nav>
    </header>

    <main>
        <section class="min-h-[80vh] flex flex-col items-center justify-center text-center px-6 py-20 bg-[radial-gradient(circle_at_top_right,_#fff5f2,_transparent_50%)] dark:bg-[radial-gradient(circle_at_top_right,_#1a100d,_transparent_50%)]">
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f5300310] text-[#f53003] text-xs font-bold mb-6 tracking-wide uppercase">
                    <span class="w-2 h-2 rounded-full bg-[#f53003] animate-pulse"></span>
                    Now with Real-time Analytics
                </div>
                <h1 class="text-5xl lg:text-8xl font-extrabold mb-8 tracking-tight leading-[0.95]">
                    Parking management <br> made <span class="gradient-text">effortless.</span>
                </h1>

                <p class="text-lg lg:text-2xl text-[#706f6c] dark:text-[#A1A09A] mb-12 max-w-2xl mx-auto leading-relaxed font-medium">
                    The modern operating system for your parking facility. Streamline logs, monitor capacity, and delight your visitors.
                </p>

                <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] font-bold rounded-full shadow-2xl hover:bg-[#333] dark:hover:bg-white hover:scale-105 transition-all">
                        Start for Free
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 border border-[#19140035] dark:border-[#3E3E3A] font-bold rounded-full hover:bg-gray-50 dark:hover:bg-zinc-900 transition-all">
                        Explore Features
                    </a>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="py-32 border-y border-[#19140008] dark:border-[#ffffff08]">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f5300310] text-[#f53003] text-xs font-bold mb-6 tracking-wide uppercase">
                    Our Process
                </div>
                <h2 class="text-4xl lg:text-5xl font-extrabold mb-16 tracking-tight">Three steps to mastery.</h2>
                
                <div class="grid md:grid-cols-3 gap-12 relative">
                    <!-- Step 1 -->
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-8 shadow-xl">
                            1
                        </div>
                        <h3 class="text-xl font-bold mb-4">Register Facility</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Sign up and configure your parking zones, slots, and operator permissions in minutes.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-[#f53003] text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-8 shadow-xl shadow-[#f5300330]">
                            2
                        </div>
                        <h3 class="text-xl font-bold mb-4">Live Monitoring</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Start logging vehicle entries and exits. Watch your dashboard update in real-time as slots fill up.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-8 shadow-xl">
                            3
                        </div>
                        <h3 class="text-xl font-bold mb-4">Analyze & Optimize</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Use historical data and peak-hour reports to optimize your space and increase your revenue.</p>
                    </div>

                    <div class="hidden md:block absolute top-8 left-[20%] right-[20%] h-0.5 border-t-2 border-dashed border-[#19140015] dark:border-[#ffffff15] -z-0"></div>
                </div>
            </div>
        </section>

        <section id="features" class="py-32 bg-[#F9F9F8] dark:bg-[#0c0c0c]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <h2 class="text-4xl lg:text-5xl font-extrabold mb-6 tracking-tight">Everything you need to <br> scale your facility.</h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg max-w-xl mx-auto">Built by experts, designed for speed, and ready for any size of operation.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="feature-card p-10 bg-white dark:bg-[#151515] rounded-3xl border border-[#19140008] dark:border-[#ffffff08]">
                        <div class="w-12 h-12 bg-[#f5300310] rounded-2xl flex items-center justify-center text-[#f53003] mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Smart Slot Mapping</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Automatically assign and track every single parking slot with pinpoint accuracy across multiple levels.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card p-10 bg-white dark:bg-[#151515] rounded-3xl border border-[#19140008] dark:border-[#ffffff08]">
                        <div class="w-12 h-12 bg-[#f5300310] rounded-2xl flex items-center justify-center text-[#f53003] mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6M6 20V10M18 20V4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Real-time Analytics</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Visualize your occupancy rates and peak hours with beautiful, easy-to-understand charts and heatmaps.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card p-10 bg-white dark:bg-[#151515] rounded-3xl border border-[#19140008] dark:border-[#ffffff08]">
                        <div class="w-12 h-12 bg-[#f5300310] rounded-2xl flex items-center justify-center text-[#f53003] mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Secure Access Control</h3>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Enterprise-grade security ensuring only authorized personnel can manage sensitive vehicle logs and data.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="py-32 bg-[#F9F9F8] dark:bg-[#0c0c0c]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-20 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f5300310] text-[#f53003] text-xs font-bold mb-6 tracking-wide uppercase">
                            Our Story
                        </div>
                        <h2 class="text-4xl lg:text-5xl font-extrabold mb-8 tracking-tight">Pioneering the future of <span class="text-[#f53003]">Commercial Mall</span> parking.</h2>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg mb-6 leading-relaxed">
                            ParkMaster is specifically engineered to handle the high-volume, high-frequency demands of <strong>Commercial Mall</strong> facilities. We understand that mall parking isn't just about slots—it's about the visitor experience.
                        </p>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg mb-8 leading-relaxed">
                            Our system streamlines the entry and exit process for thousands of shoppers daily, ensuring that your parking operations never become a bottleneck for your business.
                        </p>
                        <div class="flex gap-12">
                            <div>
                                <div class="text-3xl font-bold mb-1">2026</div>
                                <div class="text-xs font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-widest">Founded</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold mb-1">20+</div>
                                <div class="text-xs font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-widest">Experts</div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="aspect-square bg-gradient-to-br from-[#f53003] to-[#ff6b4a] rounded-[3rem] shadow-2xl flex items-center justify-center p-12 overflow-hidden">
                            <div class="text-white text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="opacity-20 absolute -top-10 -right-10"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                                <h3 class="text-3xl font-bold mb-4 italic">"Efficiency is our middle name."</h3>
                                <p class="text-lg opacity-90">- ParkMaster Team</p>
                            </div>
                        </div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-[#f5300320] rounded-full blur-3xl"></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-32 border-t border-[#19140008] dark:border-[#ffffff08]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f5300310] text-[#f53003] text-xs font-bold mb-6 tracking-wide uppercase">
                        Get in Touch
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-extrabold mb-6 tracking-tight">We're here to help.</h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg max-w-xl mx-auto">Have questions? Our support team is available 24/7 to assist you.</p>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <div class="p-10 bg-white dark:bg-[#151515] rounded-3xl border border-[#19140008] dark:border-[#ffffff08] text-center">
                        <div class="w-12 h-12 bg-[#f5300310] rounded-2xl flex items-center justify-center text-[#f53003] mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <h4 class="font-bold mb-2">Phone</h4>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">+1 (555) 000-0000</p>
                    </div>
                    <div class="p-10 bg-white dark:bg-[#151515] rounded-3xl border border-[#19140008] dark:border-[#ffffff08] text-center">
                        <div class="w-12 h-12 bg-[#f5300310] rounded-2xl flex items-center justify-center text-[#f53003] mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <h4 class="font-bold mb-2">Email</h4>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">support@parkmaster.com</p>
                    </div>
                    <div class="p-10 bg-white dark:bg-[#151515] rounded-3xl border border-[#19140008] dark:border-[#ffffff08] text-center">
                        <div class="w-12 h-12 bg-[#f5300310] rounded-2xl flex items-center justify-center text-[#f53003] mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <h4 class="font-bold mb-2">Office</h4>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">123 Innovation Way, Tech City</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-32 px-6">
            <div class="max-w-5xl mx-auto p-12 lg:p-20 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-[3rem] relative overflow-hidden text-center">
                <div class="relative z-10">
                    <h2 class="text-4xl lg:text-6xl font-extrabold mb-8 tracking-tight">Ready to master your parking?</h2>
                    <p class="text-lg lg:text-xl opacity-80 mb-12 max-w-2xl mx-auto">Join hundreds of facilities that have switched to ParkMaster for a smarter tomorrow.</p>
                    <a href="{{ route('register') }}" class="inline-block px-12 py-5 bg-[#f53003] text-white font-bold rounded-full hover:scale-105 transition-all shadow-2xl">
                        Get Started Now
                    </a>
                </div>
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-[#f53003] opacity-20 blur-[100px]"></div>
            </div>
        </section>
    </main>

    <footer class="w-full p-12 lg:p-20 border-t border-[#19140010] dark:border-[#ffffff10] bg-white dark:bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
            <div>
                <div class="text-xl font-bold tracking-tighter mb-6">
                    <span class="text-[#f53003]">Park</span>Master
                </div>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm leading-relaxed max-w-xs">
                    The world's most advanced parking management system. Simplify your operations and maximize your space.
                </p>
            </div>
            <div>
                <h4 class="font-bold mb-6">Product</h4>
                <ul class="space-y-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    <li><a href="#" class="hover:text-[#f53003] transition-colors">Features</a></li>
                    <li><a href="#" class="hover:text-[#f53003] transition-colors">Pricing</a></li>
                    <li><a href="#" class="hover:text-[#f53003] transition-colors">API Reference</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">Company</h4>
                <ul class="space-y-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    <li><a href="#" class="hover:text-[#f53003] transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-[#f53003] transition-colors">Contact</a></li>
                    <li><a href="#" class="hover:text-[#f53003] transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="pt-8 border-t border-[#19140008] dark:border-[#ffffff08] flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Built with precision.
            </p>
            <div class="flex gap-6 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                <a href="#" class="hover:text-[#f53003]">Twitter</a>
                <a href="#" class="hover:text-[#f53003]">LinkedIn</a>
                <a href="#" class="hover:text-[#f53003]">GitHub</a>
            </div>
        </div>
    </footer>

</body>
</html>