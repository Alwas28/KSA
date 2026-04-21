@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Dashboard Anggota</h2>
                <p class="text-sm text-gray-600 mt-1">Selamat datang, {{ $anggota ? $anggota->nama : Auth::user()->name }}!</p>
            </div>

            @if(!$anggota)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-bold text-yellow-800">Data Anggota Tidak Ditemukan</h3>
                            <p class="text-yellow-700 mt-1">Akun Anda belum terdaftar sebagai anggota. Silakan hubungi admin untuk pendaftaran.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Statistik Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Simpanan -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Simpanan</p>
                                <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</h3>
                                <p class="text-blue-100 text-xs mt-1">{{ $simpananPerJenis->count() }} jenis simpanan</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-piggy-bank text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pinjaman -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Total Pinjaman</p>
                                <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</h3>
                                <p class="text-green-100 text-xs mt-1">{{ $totalPinjamanAktif }} pinjaman aktif</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-hand-holding-usd text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Sisa Angsuran -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium">Sisa Angsuran</p>
                                <h3 class="text-2xl font-bold mt-2">{{ $sisaAngsuran }}</h3>
                                <p class="text-yellow-100 text-xs mt-1">Belum dibayar</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-calendar-alt text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Angsuran Terlambat -->
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-medium">Angsuran Terlambat</p>
                                <h3 class="text-2xl font-bold mt-2">{{ $angsuranTelat }}</h3>
                                <p class="text-red-100 text-xs mt-1">Segera bayar!</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-exclamation-triangle text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Simpanan per Jenis & Informasi Anggota -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Simpanan per Jenis -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-blue-600 text-white">
                            <h4 class="text-lg font-bold">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Rincian Simpanan per Jenis
                            </h4>
                        </div>
                        <div class="p-6">
                            @forelse($simpananPerJenis as $simpanan)
                                <div class="mb-4 last:mb-0">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-semibold text-gray-700">{{ $simpanan->jenisSimpanan->nama_jenis }}</span>
                                        <span class="text-sm font-bold text-blue-600">Rp {{ number_format($simpanan->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalSimpanan > 0 ? ($simpanan->total / $totalSimpanan * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $totalSimpanan > 0 ? number_format(($simpanan->total / $totalSimpanan * 100), 1) : 0 }}% dari total simpanan</span>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada simpanan</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Informasi Anggota -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-green-600 text-white">
                            <h4 class="text-lg font-bold">
                                <i class="fas fa-user-circle mr-2"></i>
                                Informasi Anggota
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-center mb-4">
                                <div class="h-20 w-20 rounded-full bg-green-600 flex items-center justify-center text-white text-3xl font-bold">
                                    {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500">No. Anggota</p>
                                    <p class="font-semibold text-gray-900">{{ $anggota->no_anggota }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Nama</p>
                                    <p class="font-semibold text-gray-900">{{ $anggota->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $anggota->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Status</p>
                                    @if($anggota->statusAnggota)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            {{ $anggota->statusAnggota->nama_status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            {{ $anggota->aktif == 'Y' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('profil-anggota.index') }}" class="mt-4 block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-edit mr-2"></i>
                                Lihat Profil Lengkap
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Angsuran Terdekat & Riwayat Simpanan -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Angsuran Terdekat -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-yellow-600 text-white flex justify-between items-center">
                            <h4 class="text-lg font-bold">
                                <i class="fas fa-calendar-check mr-2"></i>
                                Angsuran Terdekat
                            </h4>
                            <a href="{{ route('jadwal-angsuran.index') }}" class="text-sm hover:underline">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="p-6">
                            @forelse($angsuranTerdekat as $angsuran)
                                @php
                                    $isLate = \Carbon\Carbon::now()->isAfter($angsuran->tanggal_jatuh_tempo);
                                @endphp
                                <div class="mb-4 last:mb-0 p-4 rounded-lg {{ $isLate ? 'bg-red-50 border border-red-200' : 'bg-gray-50' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-900">Angsuran ke-{{ $angsuran->angsuran_ke }}</p>
                                            <p class="text-xs text-gray-600">Jatuh tempo: {{ $angsuran->tanggal_jatuh_tempo->format('d/m/Y') }}</p>
                                        </div>
                                        @if($isLate)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($angsuran->nominal_angsuran, 0, ',', '.') }}</p>
                                    @if($isLate)
                                        @php
                                            $hariTerlambat = \Carbon\Carbon::now()->diffInDays($angsuran->tanggal_jatuh_tempo);
                                            $denda = ($angsuran->nominal_angsuran * 0.01) * $hariTerlambat;
                                        @endphp
                                        <p class="text-xs text-red-600 mt-1">
                                            Terlambat {{ $hariTerlambat }} hari • Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-check-circle text-4xl mb-3 text-gray-300"></i>
                                    <p>Tidak ada angsuran yang belum dibayar</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Riwayat Simpanan Terbaru -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-blue-600 text-white flex justify-between items-center">
                            <h4 class="text-lg font-bold">
                                <i class="fas fa-history mr-2"></i>
                                Riwayat Simpanan Terbaru
                            </h4>
                            <a href="{{ route('simpanan-saya.index') }}" class="text-sm hover:underline">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="p-6">
                            @forelse($riwayatSimpanan as $simpanan)
                                <div class="mb-4 last:mb-0 p-4 rounded-lg bg-gray-50">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $simpanan->jenisSimpanan->nama_jenis }}</p>
                                            <p class="text-xs text-gray-600">{{ $simpanan->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-up mr-1"></i>
                                            Setoran
                                        </span>
                                    </div>
                                    <p class="text-lg font-bold text-green-600">+ Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</p>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada riwayat simpanan</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Status Pinjaman Terbaru -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-green-600 text-white flex justify-between items-center">
                        <h4 class="text-lg font-bold">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            Status Pinjaman Terbaru
                        </h4>
                        <a href="{{ route('pinjaman-saya.index') }}" class="text-sm hover:underline">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal Pengajuan</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Pokok Pinjaman</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Lama Angsuran</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status Angsuran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($pinjamanTerbaru as $pinjaman)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pinjaman->tanggal_pengajuan->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900">Rp {{ number_format($pinjaman->pokok_pinjaman, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-sm text-center">{{ $pinjaman->lama_angsuran }} bulan</td>
                                            <td class="px-4 py-3 text-center">
                                                @if($pinjaman->status == 'disetujui')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Disetujui
                                                    </span>
                                                @elseif($pinjaman->status == 'diajukan')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Menunggu
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if($pinjaman->status_angsuran == 'aktif')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                        Aktif ({{ $pinjaman->sisa_angsuran }}/{{ $pinjaman->lama_angsuran }})
                                                    </span>
                                                @elseif($pinjaman->status_angsuran == 'selesai')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        Lunas
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                                <p>Belum ada pengajuan pinjaman</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection
