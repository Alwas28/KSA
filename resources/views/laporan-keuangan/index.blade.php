@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h2>
                <p class="text-sm text-gray-600 mt-1">Pilih jenis laporan keuangan yang ingin Anda lihat</p>
            </div>

            <!-- Menu Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Laporan Laba Rugi -->
                <a href="{{ route('laporan-keuangan.laba-rugi') }}" class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-green-100 p-4 rounded-full">
                                <i class="fas fa-chart-line text-green-600 text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Laba Rugi</h3>
                        <p class="text-gray-600 text-sm">Ringkasan pendapatan dan beban koperasi dalam periode tertentu</p>
                        <div class="mt-4 flex items-center text-green-600 font-semibold">
                            <span>Lihat Laporan</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </a>

                <!-- Laporan Neraca -->
                <a href="{{ route('laporan-keuangan.neraca') }}" class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-blue-100 p-4 rounded-full">
                                <i class="fas fa-balance-scale text-blue-600 text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Neraca</h3>
                        <p class="text-gray-600 text-sm">Posisi keuangan koperasi: aktiva, pasiva, dan modal</p>
                        <div class="mt-4 flex items-center text-blue-600 font-semibold">
                            <span>Lihat Laporan</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </a>

                <!-- Laporan Arus Kas -->
                <a href="{{ route('laporan-keuangan.arus-kas') }}" class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-purple-100 p-4 rounded-full">
                                <i class="fas fa-money-bill-wave text-purple-600 text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Arus Kas</h3>
                        <p class="text-gray-600 text-sm">Aliran kas masuk dan keluar dalam periode tertentu</p>
                        <div class="mt-4 flex items-center text-purple-600 font-semibold">
                            <span>Lihat Laporan</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </a>

                <!-- Laporan Simpanan -->
                <a href="{{ route('laporan-keuangan.simpanan') }}" class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-yellow-100 p-4 rounded-full">
                                <i class="fas fa-piggy-bank text-yellow-600 text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Simpanan</h3>
                        <p class="text-gray-600 text-sm">Rekap simpanan anggota per jenis simpanan</p>
                        <div class="mt-4 flex items-center text-yellow-600 font-semibold">
                            <span>Lihat Laporan</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </a>

                <!-- Laporan Pinjaman -->
                <a href="{{ route('laporan-keuangan.pinjaman') }}" class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-red-100 p-4 rounded-full">
                                <i class="fas fa-hand-holding-usd text-red-600 text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Pinjaman</h3>
                        <p class="text-gray-600 text-sm">Rekap pinjaman dan angsuran anggota</p>
                        <div class="mt-4 flex items-center text-red-600 font-semibold">
                            <span>Lihat Laporan</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </a>

                <!-- Buku Kas (Link to existing) -->
                <a href="{{ route('buku-kas.index') }}" class="block bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-indigo-100 p-4 rounded-full">
                                <i class="fas fa-book text-indigo-600 text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Buku Kas</h3>
                        <p class="text-gray-600 text-sm">Catatan keluar masuk kas koperasi dengan saldo berjalan</p>
                        <div class="mt-4 flex items-center text-indigo-600 font-semibold">
                            <span>Lihat Buku Kas</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Info Section -->
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-blue-800">Informasi</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            Semua laporan keuangan dapat difilter berdasarkan periode. Gunakan fitur filter di setiap halaman laporan untuk melihat data sesuai periode yang diinginkan.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
