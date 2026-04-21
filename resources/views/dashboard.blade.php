@extends('layouts.users')
@section('css')
    <style>
        /* Mengatur scrollbar untuk area konten */
        .content-area::-webkit-scrollbar {
            width: 8px;
        }
        .content-area::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 10px;
        }
    </style>
@endsection

@section('konten')
    <!-- Konten Utama (Header & Body) -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        @include('components.navbar')

        <!-- Area Konten & Dashboard Ringkasan -->
        <main class="content-area flex-1 overflow-y-auto p-6">
            
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Ringkasan Koperasi</h2>

            <!-- Dashboard Ringkasan (Card Metrik) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Card 1: Jumlah Anggota (Green-Light) -->
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-simkop-green-light">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Anggota Aktif</p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-1">2.540</p>
                        </div>
                        <i class="fas fa-user-check text-3xl text-simkop-green-light opacity-50"></i>
                    </div>
                </div>

                <!-- Card 2: Total Simpanan (Green-500 - sudah hijau) -->
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Simpanan</p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-1">Rp 1.2 M</p>
                        </div>
                        <i class="fas fa-coins text-3xl text-green-500 opacity-50"></i>
                    </div>
                </div>

                <!-- Card 3: Pinjaman Berjalan (Yellow - Tetap) -->
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pinjaman Berjalan</p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-1">Rp 750 Jt</p>
                        </div>
                        <i class="fas fa-hand-holding-usd text-3xl text-yellow-500 opacity-50"></i>
                    </div>
                </div>

                <!-- Card 4: Tunggakan/Denda (Red - Tetap) -->
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tunggakan (30+ Hari)</p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-1">Rp 55 Jt</p>
                        </div>
                        <i class="fas fa-exclamation-circle text-3xl text-red-500 opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Bagian Laporan dan Aktivitas Terbaru -->
            <div class="grid lg:grid-cols-3 gap-6">
                
                <!-- Kolom Kiri (Grafik Ringkasan) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Grafik Pertumbuhan Simpanan vs Pinjaman (Placeholder) -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold mb-4">Grafik Pertumbuhan Dana</h3>
                        <div class="h-64 bg-gray-100 flex items-center justify-center text-gray-500 rounded-lg">
                            [Placeholder Grafik Bar/Garis: Simpanan vs Pinjaman Bulanan]
                        </div>
                    </div>
                    
                    <!-- Pengajuan Pinjaman Baru (Placeholder Tabel) -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold mb-4">Pengajuan Pinjaman Baru (5 Terbaru)</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ahmad B.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 15 Jt</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Siti N.</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 5 Jt</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="#" class="mt-4 text-sm text-simkop-green-dark hover:underline block text-right">Lihat Semua Pengajuan &rarr;</a>
                    </div>
                </div>

                <!-- Kolom Kanan (Aktivitas Terakhir & Shortcut) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Laporan SHU Cepat -->
                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-yellow-600">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Laporan SHU Anggota</h3>
                        <p class="text-gray-600 mb-3 text-sm">Hitung Sisa Hasil Usaha (SHU) secara otomatis untuk periode tertentu.</p>
                        <button class="w-full bg-yellow-500 text-white py-2 rounded-lg font-semibold hover:bg-yellow-600 transition duration-150">
                            <i class="fas fa-calculator mr-2"></i> Generate SHU 2024
                        </button>
                    </div>

                    <!-- Aktivitas Sistem Terbaru -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-xl font-semibold mb-4">Aktivitas Terbaru</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start text-sm">
                                <i class="fas fa-circle text-simkop-green-light text-xs mt-1 mr-3 flex-shrink-0"></i>
                                <p>Setoran <span class="font-semibold">Simpanan Sukarela</span> dari Bpk. Taufik.</p>
                            </li>
                            <li class="flex items-start text-sm">
                                <i class="fas fa-circle text-green-500 text-xs mt-1 mr-3 flex-shrink-0"></i>
                                <p>Pinjaman <span class="font-semibold">Rp 10 Jt</span> disetujui untuk Ibu Wina.</p>
                            </li>
                            <li class="flex items-start text-sm">
                                <i class="fas fa-circle text-red-500 text-xs mt-1 mr-3 flex-shrink-0"></i>
                                <p>Peringatan <span class="font-semibold">Tunggakan</span> untuk 12 anggota.</p>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </main>
    </div>
@endsection

@section('js')
    <!-- JavaScript untuk Toggle Sidebar & Submenu -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebar-toggle');
            const closeButton = document.getElementById('sidebar-close');
            const backdrop = document.getElementById('backdrop');

            // --- Logika Toggle Sidebar (Mobile) ---
            const toggleSidebar = () => {
                const isOpen = sidebar.classList.contains('translate-x-0');

                if (isOpen) {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    backdrop.classList.remove('hidden');
                }
            };

            if (toggleButton) {
                toggleButton.addEventListener('click', toggleSidebar);
            }
            if (closeButton) {
                closeButton.addEventListener('click', toggleSidebar);
            }
            if (backdrop) {
                backdrop.addEventListener('click', toggleSidebar);
            }
            
            // Tutup sidebar saat link diklik (di mobile)
            const menuLinks = sidebar.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) { // Cek jika di bawah breakpoint 'md'
                        toggleSidebar();
                    }
                });
            });


            // --- Logika Toggle Submenu (Collapsible) ---
            const submenuToggles = document.querySelectorAll('[data-submenu-toggle]');

            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = toggle.getAttribute('data-submenu-toggle');
                    const submenu = document.getElementById(`${targetId}-submenu`);
                    const arrow = document.querySelector(`[data-arrow="${targetId}"]`);
                    
                    if (submenu && arrow) {
                        const isHidden = submenu.classList.contains('hidden');
                        
                        // Close all submenus first
                        document.querySelectorAll('[data-submenu-toggle]').forEach(otherToggle => {
                            const otherTargetId = otherToggle.getAttribute('data-submenu-toggle');
                            const otherSubmenu = document.getElementById(`${otherTargetId}-submenu`);
                            const otherArrow = document.querySelector(`[data-arrow="${otherTargetId}"]`);
                            
                            if (otherSubmenu && otherArrow && !otherSubmenu.classList.contains('hidden')) {
                                otherSubmenu.classList.add('hidden');
                                otherArrow.classList.remove('rotate-180');
                            }
                        });

                        if (isHidden) {
                            // Open current submenu
                            submenu.classList.remove('hidden');
                            arrow.classList.add('rotate-180');
                        }
                        // If it was open, it's already closed by the loop above, so nothing further needed.
                    }
                });
            });
        });
    </script>
@endsection
