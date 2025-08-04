@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800 text-white" x-data="{
        currentSlide: 0,
        slides: [{
                title: 'Transparansi Pelayanan Publik',
                subtitle: 'Melayani dengan integritas dan akuntabilitas tinggi',
                image: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=800&h=600&fit=crop'
            },
            {
                title: 'Pengawasan Berkualitas',
                subtitle: 'Memastikan kualitas pelayanan yang optimal',
                image: 'https://images.unsplash.com/photo-1573164713988-8665fc963095?w=800&h=600&fit=crop'
            },
            {
                title: 'Pelaporan Mudah & Cepat',
                subtitle: 'Platform digital untuk kemudahan pelaporan masyarakat',
                image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop'
            }
        ]
    }"
        x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 5000)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        <span x-text="slides[currentSlide].title"></span>
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                        <span x-text="slides[currentSlide].subtitle"></span>
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @guest
                            <a href=""
                                class="bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                Masuk Kesini untuk Testing CI/CD
                            </a>
                            <a href="#layanan"
                                class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-primary-700 transition-colors duration-200 text-center">
                                Pelajari Lebih Lanjut
                            </a>
                        @else
                            @if (auth()->user()->role === 'Warga')
                                <a href=""
                                    class="bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                    Buat Laporan
                                </a>
                                <a href=""
                                    class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-primary-700 transition-colors duration-200 text-center">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ auth()->user()->role === 'Admin'
                                    ? route('admin.dashboard')
                                    : (auth()->user()->role === 'Ketua_Bidang_Investigasi'
                                        ? route('ketua_bidang.dashboard')
                                        : route('pegawai.dashboard')) }}"
                                    class="bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors duration-200 text-center">
                                    Dashboard
                                </a>
                            @endif
                        @endguest
                    </div>

                    <!-- Slide Indicators -->
                    <div class="flex justify-center lg:justify-start mt-8 space-x-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="currentSlide = index"
                                class="w-3 h-3 rounded-full transition-colors duration-200"
                                :class="currentSlide === index ? 'bg-white' : 'bg-blue-300'">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Image -->
                <div class="relative">
                    <div class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden shadow-2xl">
                        <img :src="slides[currentSlide].image" :alt="slides[currentSlide].title"
                            class="w-full h-96 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="p-6">
                    <div class="text-4xl font-bold text-primary-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => {
                        let target = 1500;
                        let increment = target / 100;
                        let timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                        }, 20);
                    }, 200)">
                        <span x-text="Math.floor(count)"></span>+
                    </div>
                    <div class="text-gray-600 font-medium">Laporan Terproses</div>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold text-primary-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => {
                        let target = 95;
                        let increment = target / 100;
                        let timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                        }, 20);
                    }, 400)">
                        <span x-text="Math.floor(count)"></span>%
                    </div>
                    <div class="text-gray-600 font-medium">Tingkat Kepuasan</div>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold text-primary-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => {
                        let target = 24;
                        let increment = target / 100;
                        let timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                        }, 20);
                    }, 600)">
                        <span x-text="Math.floor(count)"></span>h
                    </div>
                    <div class="text-gray-600 font-medium">Waktu Respon</div>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold text-primary-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => {
                        let target = 50;
                        let increment = target / 100;
                        let timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                        }, 20);
                    }, 800)">
                        <span x-text="Math.floor(count)"></span>+
                    </div>
                    <div class="text-gray-600 font-medium">Tim Ahli</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Tentang Sistem Pelaporan Inspektorat
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Platform digital yang dirancang untuk meningkatkan transparansi, akuntabilitas, dan kualitas
                        pelayanan publik.
                        Kami berkomitmen untuk melayani masyarakat dengan integritas tinggi dan profesionalisme.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Transparansi Penuh</h3>
                                <p class="text-gray-600">Semua proses dapat dipantau secara real-time oleh masyarakat.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Keamanan Data</h3>
                                <p class="text-gray-600">Privasi dan keamanan informasi terjamin dengan teknologi terkini.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Respon Cepat</h3>
                                <p class="text-gray-600">Sistem otomatis memastikan respon yang cepat dan akurat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=600&fit=crop"
                        alt="Tim Inspektorat" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Layanan Kami
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Berbagai layanan profesional untuk membantu masyarakat dalam pelaporan dan pengawasan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-300"
                    x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Pelaporan Online</h3>
                    <p class="text-gray-600 mb-6">
                        Platform digital untuk mengajukan laporan dengan mudah, cepat, dan aman. Tersedia 24/7 untuk
                        kemudahan akses.
                    </p>
                    <a href=""
                        class="inline-flex items-center text-primary-600 font-medium hover:text-primary-700 transition-colors duration-200">
                        Mulai Laporan
                        <svg class="ml-2 w-4 h-4 transition-transform duration-200" :class="hover ? 'translate-x-1' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Service 2 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-300"
                    x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Monitoring Progres</h3>
                    <p class="text-gray-600 mb-6">
                        Pantau perkembangan laporan Anda secara real-time dengan sistem tracking yang transparan dan akurat.
                    </p>
                    <a href="#"
                        class="inline-flex items-center text-primary-600 font-medium hover:text-primary-700 transition-colors duration-200">
                        Lihat Status
                        <svg class="ml-2 w-4 h-4 transition-transform duration-200" :class="hover ? 'translate-x-1' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Service 3 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-300"
                    x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Tim Profesional</h3>
                    <p class="text-gray-600 mb-6">
                        Ditangani oleh tim ahli yang berpengalaman dalam investigasi dan penyelesaian berbagai kasus.
                    </p>
                    <a href="#"
                        class="inline-flex items-center text-primary-600 font-medium hover:text-primary-700 transition-colors duration-200">
                        Pelajari Tim
                        <svg class="ml-2 w-4 h-4 transition-transform duration-200" :class="hover ? 'translate-x-1' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Siap untuk Memulai Laporan?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan masyarakat yang telah mempercayai platform kami untuk transparansi dan
                akuntabilitas.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                    <a href=""
                        class="bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors duration-200">
                        Masuk ke Portal
                    </a>
                    <a href="#kontak"
                        class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-primary-700 transition-colors duration-200">
                        Hubungi Kami
                    </a>
                @else
                    @if (auth()->user()->role === 'Warga')
                        <a href=""
                            class="bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors duration-200">
                            Buat Laporan Baru
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Hubungi Kami
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Kami siap membantu Anda 24/7. Jangan ragu untuk menghubungi tim kami.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Alamat</h3>
                            <p class="text-gray-600">Jl. Inspektorat No. 123<br>Makassar, Sulawesi Selatan 90111</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Telepon</h3>
                            <p class="text-gray-600">(0411) 123-4567<br>Senin - Jumat, 08:00 - 17:00</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                            <p class="text-gray-600">info@inspektorat.go.id<br>hotline@inspektorat.go.id</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <form class="bg-white p-8 rounded-xl shadow-lg" x-data="{ submitting: false }"
                        @submit.prevent="submitting = true; setTimeout(() => { submitting = false; $event.target.reset(); alert('Pesan berhasil dikirim!'); }, 2000)">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-200">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-200">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                            <input type="text" id="subject" name="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-200">
                        </div>
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="6" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors duration-200"></textarea>
                        </div>
                        <button type="submit" :disabled="submitting"
                            class="w-full bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting">Kirim Pesan</span>
                            <span x-show="submitting" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Mengirim...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
