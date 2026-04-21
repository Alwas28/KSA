<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSA UMKendari- Koperasi Surya Amaliah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Konfigurasi Warna Kustom (Hijau Koperasi) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Tema Hijau: Simpanan, Alam, Pertumbuhan
                        'simkop-green-dark': '#047857', // Hijau Tua (Green-700)
                        'simkop-green-light': '#10b981', // Hijau Cerah (Green-500)
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @yield('css')
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">
    
    <!-- Backdrop/Overlay Mobile -->
    <div id="backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" aria-hidden="true"></div>

    <!-- Sidebar Navigasi -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 z-50 bg-simkop-green-dark text-white flex flex-col shadow-lg 
        transform -translate-x-full transition duration-300 ease-in-out 
        md:relative md:translate-x-0 md:flex md:w-64 md:flex-shrink-0">
        
        <!-- Logo & Nama Aplikasi -->
        <div class="p-6 border-b border-green-800 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-cubes text-2xl text-simkop-green-light mr-3"></i>
                <h1 class="text-xl font-bold tracking-wider">KSA UMKendari</h1>
            </div>
            <!-- Tombol Tutup Sidebar (Hanya Mobile) -->
            <button id="sidebar-close" class="md:hidden text-white hover:text-gray-300 focus:outline-none p-1" title="Tutup Menu" aria-label="Tutup Menu">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg bg-simkop-green-light font-semibold shadow-md transition duration-150">
                <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->hasPermission('anggota.read'))

                <!-- A. Manajemen Keanggotaan -->
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Keanggotaan</h2>

                <a href="{{ route('pendaftaran-anggota.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('pendaftaran-anggota.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-user-plus w-5 mr-3"></i>
                    <span>Pendaftaran Anggota</span>
                </a>
                <a href="{{ route('anggota.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('anggota.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    <span>Data Anggota</span>
                </a>

            @endif
            <!-- Menu Anggota (Member Role) -->
            <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Menu Anggota</h2>
            <a href="{{ route('profil-anggota.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('profil-anggota.*') ? 'bg-simkop-green-light' : '' }}">
                <i class="fas fa-user-circle w-5 mr-3"></i>
                <span>Profil Saya</span>
            </a>
            <a href="{{ route('simpanan-saya.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('simpanan-saya.*') ? 'bg-simkop-green-light' : '' }}">
                <i class="fas fa-piggy-bank w-5 mr-3"></i>
                <span>Simpanan Saya</span>
            </a>
            <a href="{{ route('pinjaman-saya.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('pinjaman-saya.*') ? 'bg-simkop-green-light' : '' }}">
                <i class="fas fa-hand-holding-usd w-5 mr-3"></i>
                <span>Pinjaman Saya</span>
            </a>
            <a href="{{ route('jadwal-angsuran.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('jadwal-angsuran.*') ? 'bg-simkop-green-light' : '' }}">
                <i class="fas fa-calendar-alt w-5 mr-3"></i>
                <span>Jadwal Angsuran Saya</span>
            </a>
            <a href="{{ route('riwayat-transaksi.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('riwayat-transaksi.*') ? 'bg-simkop-green-light' : '' }}">
                <i class="fas fa-history w-5 mr-3"></i>
                <span>Riwayat Transaksi</span>
            </a>
            <a href="{{ route('riwayat-shu.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('riwayat-shu.*') ? 'bg-simkop-green-light' : '' }}">
                <i class="fas fa-chart-pie w-5 mr-3"></i>
                <span>Riwayat SHU</span>
            </a>
            {{-- <a href="{{ route('saldo-simpanan.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('saldo-simpanan.*') ? 'bg-simkop-green-light' : '' }}">
                <i class="fas fa-wallet w-5 mr-3"></i>
                <span>Saldo Simpanan</span>
            </a> --}}

            @if(auth()->user()->hasPermission('simpanan.read'))

                <!-- B. Manajemen Simpanan -->
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Manajemen Simpanan</h2>
                <a href="{{ route('jenis-simpanan.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('jenis-simpanan.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-piggy-bank w-5 mr-3"></i>
                    <span>Jenis Simpanan</span>
                </a>
                <a href="{{ route('simpanan.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('simpanan.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-plus-circle w-5 mr-3"></i>
                    <span>Data Simpanan</span>
                </a>
                <a href="#" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150">
                    <i class="fas fa-chart-bar w-5 mr-3"></i>
                    <span>Rekap Simpanan</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('pinjaman.read'))

                <!-- C. Manajemen Pinjaman -->
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Manajemen Pinjaman</h2>
                <a href="{{ route('pinjaman.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('pinjaman.*') && !request()->routeIs('pinjaman-saya.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-file-contract w-5 mr-3"></i>
                    <span>Data Pinjaman</span>
                </a>
                <a href="{{ route('persetujuan-pinjaman.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('persetujuan-pinjaman.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-check-circle w-5 mr-3"></i>
                    <span>Persetujuan & Cair</span>
                </a>
                <a href="{{ route('pembayaran-angsuran.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('pembayaran-angsuran.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-money-bill-wave w-5 mr-3"></i>
                    <span>Pembayaran Angsuran</span>
                </a>
                <a href="#" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150">
                    <i class="fas fa-exclamation-triangle w-5 mr-3"></i>
                    <span>Tunggakan & Denda</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('simpanan.read'))

                <!-- D. Buku KAS -->
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Keuangan</h2>
                <a href="{{ route('buku-kas.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('buku-kas.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-book w-5 mr-3"></i>
                    <span>Buku Kas</span>
                </a>

            @endif

            {{-- F. Laporan --}}
            @if(auth()->user()->hasPermission('laporan_keuangan.read') || auth()->user()->hasPermission('laporan_shu.read'))
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Laporan</h2>
                @if(auth()->user()->hasPermission('laporan_keuangan.read'))
                    <a href="{{ route('laporan-keuangan.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('laporan-keuangan.*') ? 'bg-simkop-green-light' : '' }}">
                        <i class="fas fa-file-pdf w-5 mr-3"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                @endif
                @if(auth()->user()->hasPermission('laporan_shu.read'))
                    <a href="{{ route('shu.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('shu.*') ? 'bg-simkop-green-light' : '' }}">
                        <i class="fas fa-chart-pie w-5 mr-3"></i>
                        <span>SHU</span>
                    </a>
                @endif
            @endif

            {{-- F.1 Arsip File --}}
            @if(auth()->user()->hasPermission('arsip.read'))
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Arsip</h2>
                <a href="{{ route('arsip-file.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('arsip-file.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-folder-open w-5 mr-3"></i>
                    <span>Arsip File</span>
                </a>
            @endif

            {{-- F.2 Kegiatan Usaha --}}
            @if(auth()->user()->hasPermission('master_kegiatan.read'))
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Kegiatan Usaha</h2>
                <a href="{{ route('kegiatan-usaha.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('kegiatan-usaha.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-briefcase w-5 mr-3"></i>
                    <span>Master Kegiatan</span>
                </a>
                <a href="{{ route('transaksi-kegiatan.index') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition duration-150 {{ request()->routeIs('transaksi-kegiatan.*') ? 'bg-simkop-green-light' : '' }}">
                    <i class="fas fa-exchange-alt w-5 mr-3"></i>
                    <span>Transaksi Kegiatan</span>
                </a>
            @endif

            {{-- G. Manajemen Website --}}
            @php
                $showMasterData    = auth()->user()->hasPermission('menu_master_data.read');
                $showManajemenPost = auth()->user()->hasPermission('menu_manajemen_post.read');
                $showManajemenAkses = auth()->user()->hasPermission('menu_manajemen_akses.read');
            @endphp

            @if($showMasterData || $showManajemenPost || $showManajemenAkses)
                <h2 class="text-xs font-semibold text-gray-300 uppercase pt-4 pb-1 px-3">Manajemen Website</h2>
            @endif

            {{-- 1. Master Data (Collapsible) --}}
            @if($showMasterData)
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('menu-manajemen')" class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-green-600 transition duration-150 text-left">
                        <div class="flex items-center">
                            <i class="fas fa-bars w-5 mr-3"></i>
                            <span>Master Data</span>
                        </div>
                        <i id="menu-manajemen-arrow" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    <div id="menu-manajemen-submenu" class="pl-8 space-y-1 hidden">
                        <a href="{{ route('status-anggota.index') }}" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm {{ request()->routeIs('status-anggota.*') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-user-check w-4 mr-3"></i> Status Anggota
                        </a>
                    </div>
                </div>
            @endif

            {{-- 2. Manajemen Post (Collapsible) --}}
            @if($showManajemenPost)
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('menu-post')" class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-green-600 transition duration-150 text-left">
                        <div class="flex items-center">
                            <i class="fas fa-newspaper w-5 mr-3"></i>
                            <span>Manajemen Post</span>
                        </div>
                        <i id="menu-post-arrow" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    <div id="menu-post-submenu" class="pl-8 space-y-1 hidden">
                        <a href="#" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm">
                            <i class="fas fa-bullhorn w-4 mr-3"></i> Berita
                        </a>
                        <a href="#" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm">
                            <i class="fas fa-scroll w-4 mr-3"></i> Pengumuman
                        </a>
                        <a href="#" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm">
                            <i class="fas fa-file-alt w-4 mr-3"></i> Page (Halaman)
                        </a>
                    </div>
                </div>
            @endif

            {{-- 3. Manajemen Akses (Collapsible) --}}
            @if($showManajemenAkses)
                <div class="space-y-1">
                    <button type="button" onclick="toggleSubmenu('manajemen-akses')" class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-green-600 transition duration-150 text-left">
                        <div class="flex items-center">
                            <i class="fas fa-user-shield w-5 mr-3"></i>
                            <span>Manajemen Akses</span>
                        </div>
                        <i id="manajemen-akses-arrow" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    <div id="manajemen-akses-submenu" class="pl-8 space-y-1 hidden">
                        @if(auth()->user()->hasPermission('user.read'))
                            <a href="{{ route('users.index') }}" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm {{ request()->routeIs('users.*') ? 'bg-green-700' : '' }}">
                                <i class="fas fa-users w-4 mr-3"></i> User
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('role.read'))
                            <a href="{{ route('roles.index') }}" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm {{ request()->routeIs('roles.*') ? 'bg-green-700' : '' }}">
                                <i class="fas fa-user-tag w-4 mr-3"></i> Role
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('permission.read'))
                            <a href="{{ route('permissions.index') }}" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm {{ request()->routeIs('permissions.*') ? 'bg-green-700' : '' }}">
                                <i class="fas fa-key w-4 mr-3"></i> Permission
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('user_role.read'))
                            <a href="{{ route('user-roles.index') }}" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm {{ request()->routeIs('user-roles.*') ? 'bg-green-700' : '' }}">
                                <i class="fas fa-user-shield w-4 mr-3"></i> User Role
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('role_permission.read'))
                            <a href="{{ route('role-permissions.index') }}" class="flex items-center p-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm {{ request()->routeIs('role-permissions.*') ? 'bg-green-700' : '' }}">
                                <i class="fas fa-link w-4 mr-3"></i> Role Permission
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </nav>
        
        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-green-800 text-sm text-center flex-shrink-0">
            <p>Koperasi Surya Amaliah © 2026</p>
        </div>
    </aside>

    @yield('konten')

    <script>
        // Toggle Sidebar Mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const backdrop = document.getElementById('backdrop');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            });
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            });
        }

        // Toggle Submenu Function
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId + '-submenu');
            const arrow = document.getElementById(menuId + '-arrow');

            if (submenu) {
                submenu.classList.toggle('hidden');
            }

            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }
    </script>

    @yield('js')
</body>
</html>