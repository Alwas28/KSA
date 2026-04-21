<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Surya Amaliah - UMKendari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .mobile-menu-closed {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            padding-top: 0;
            padding-bottom: 0;
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }
        .mobile-menu-open {
            max-height: 600px;
            opacity: 1;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }
    </style>
</head>
<body class="font-sans text-gray-800">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 flex-shrink-0">
                <i class="fas fa-handshake text-koperasi-primary text-2xl"></i>
                {{-- Desktop: nama panjang | Mobile: singkatan --}}
                <span class="hidden md:inline text-xl font-bold text-gray-900">
                    Koperasi <span class="text-koperasi-primary">Surya Amaliah</span>
                </span>
                <span class="md:hidden text-xl font-bold text-gray-900">
                    <span class="text-koperasi-primary">KSA</span>
                </span>
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex space-x-8 text-sm font-medium">
                <a href="#profil"  class="hover:text-koperasi-primary transition duration-150">Profil Koperasi</a>
                <a href="#produk"  class="hover:text-koperasi-primary transition duration-150">Produk & Layanan</a>
                <a href="#berita"  class="hover:text-koperasi-primary transition duration-150">Berita & Pengumuman</a>
                <a href="#visi"    class="hover:text-koperasi-primary transition duration-150">Visi Misi</a>
                <a href="#kontak"  class="hover:text-koperasi-primary transition duration-150">Kontak</a>
            </div>

            {{-- Tombol Auth & Hamburger --}}
            <div class="flex items-center space-x-2 md:space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="bg-koperasi-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition duration-150">
                        <i class="fas fa-tachometer-alt mr-1"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition duration-150">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-koperasi-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition duration-150">
                        <i class="fas fa-user-circle mr-1"></i> Login
                    </a>
                    {{-- Daftar: hanya tampil di desktop --}}
                    <a href="{{ route('register') }}"
                        class="hidden md:inline-flex bg-koperasi-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition duration-150">
                        <i class="fas fa-user-plus mr-1"></i> Daftar
                    </a>
                @endauth

                {{-- Tombol Hamburger Mobile --}}
                <button id="mobile-menu-button"
                    class="md:hidden text-gray-600 hover:text-koperasi-primary focus:outline-none p-2 rounded-md hover:bg-gray-100 transition duration-150"
                    aria-label="Toggle menu">
                    <i class="fas fa-bars text-xl" id="menu-icon"></i>
                </button>
            </div>
        </nav>

        {{-- Menu Mobile --}}
        <div id="mobile-menu" class="md:hidden bg-gray-50 border-t border-gray-200 mobile-menu-closed">
            <div class="px-4 py-2 space-y-1 font-medium text-gray-700">
                <a href="#profil" class="mobile-link block rounded-md px-3 py-2 hover:bg-koperasi-primary hover:text-white transition duration-150">
                    <i class="fas fa-building w-5 mr-2"></i>Profil Koperasi
                </a>
                <a href="#produk" class="mobile-link block rounded-md px-3 py-2 hover:bg-koperasi-primary hover:text-white transition duration-150">
                    <i class="fas fa-box-open w-5 mr-2"></i>Produk & Layanan
                </a>
                <a href="#berita" class="mobile-link block rounded-md px-3 py-2 hover:bg-koperasi-primary hover:text-white transition duration-150">
                    <i class="fas fa-newspaper w-5 mr-2"></i>Berita & Pengumuman
                </a>
                <a href="#visi" class="mobile-link block rounded-md px-3 py-2 hover:bg-koperasi-primary hover:text-white transition duration-150">
                    <i class="fas fa-bullseye w-5 mr-2"></i>Visi Misi
                </a>
                <a href="#kontak" class="mobile-link block rounded-md px-3 py-2 hover:bg-koperasi-primary hover:text-white transition duration-150">
                    <i class="fas fa-phone-alt w-5 mr-2"></i>Kontak
                </a>
            </div>
        </div>
    </header>

    @yield('konten')

    <footer class="bg-gray-800 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">&copy; 2026 Koperasi Surya Amaliah. Semua Hak Dilindungi.</p>
            <div class="mt-3 space-x-4">
                <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>

    @yield('js')

    {{-- Toggle Mobile Menu --}}
    <script>
        (function () {
            const btn    = document.getElementById('mobile-menu-button');
            const menu   = document.getElementById('mobile-menu');
            const icon   = document.getElementById('menu-icon');
            const links  = menu.querySelectorAll('.mobile-link');

            function toggle() {
                const closed = menu.classList.contains('mobile-menu-closed');
                menu.classList.toggle('mobile-menu-closed', !closed);
                menu.classList.toggle('mobile-menu-open', closed);
                icon.classList.toggle('fa-bars', !closed);
                icon.classList.toggle('fa-times', closed);
            }

            btn.addEventListener('click', toggle);
            links.forEach(function (link) {
                link.addEventListener('click', function () {
                    if (menu.classList.contains('mobile-menu-open')) toggle();
                });
            });
        })();
    </script>
</body>
</html>
