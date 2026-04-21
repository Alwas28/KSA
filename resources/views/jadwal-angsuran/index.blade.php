@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Jadwal Angsuran Saya</h2>
                        <p class="text-sm text-gray-600 mt-1">Lihat jadwal dan riwayat pembayaran angsuran pinjaman Anda</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Pinjaman</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalPinjaman }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-file-invoice-dollar text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Sudah Dibayar</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalAngsuranSudahBayar }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Belum Dibayar</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalAngsuranBelumBayar }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Terlambat</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalAngsuranTelat }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pinjaman -->
            @forelse($pinjaman as $item)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <!-- Header Pinjaman -->
                    <div class="px-6 py-4 bg-green-600 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-lg font-bold">
                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                    Pinjaman {{ $item->tanggal_pencairan ? $item->tanggal_pencairan->format('F Y') : '' }}
                                </h4>
                                <p class="text-sm text-green-100 mt-1">
                                    Dicairkan: {{ $item->tanggal_pencairan ? $item->tanggal_pencairan->format('d/m/Y') : '-' }}
                                </p>
                            </div>
                            <div class="text-right">
                                @if($item->status_angsuran == 'aktif')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white bg-opacity-20">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Aktif
                                    </span>
                                @elseif($item->status_angsuran == 'selesai')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white bg-opacity-20">
                                        <i class="fas fa-flag-checkered mr-2"></i>
                                        Selesai
                                    </span>
                                @elseif($item->status_angsuran == 'macet')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-900 bg-opacity-50">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Macet
                                    </span>
                                @endif
                                <p class="text-sm text-green-100 mt-1">
                                    Sisa: {{ $item->sisa_angsuran }} / {{ $item->lama_angsuran }} bulan
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Pinjaman -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Pokok Pinjaman</p>
                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Bunga ({{ $item->bunga_persen }}%)</p>
                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($item->total_bunga, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Pinjaman</p>
                                <p class="text-sm font-semibold text-green-600">Rp {{ number_format($item->total_pinjaman, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Angsuran per Bulan</p>
                                <p class="text-sm font-semibold text-blue-600">Rp {{ number_format($item->angsuran_per_bulan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Angsuran -->
                    <div class="overflow-x-auto p-6">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Angsuran</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jatuh Tempo</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Nominal</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Bayar</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Denda</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($item->angsuran as $angsuran)
                                    @php
                                        $isLate = $angsuran->status == 'belum_bayar' && \Carbon\Carbon::now()->isAfter($angsuran->tanggal_jatuh_tempo);
                                    @endphp
                                    <tr class="{{ $isLate ? 'bg-red-50' : 'hover:bg-gray-50' }} transition-colors duration-150">
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $angsuran->status == 'dibayar' ? 'bg-green-600' : ($isLate ? 'bg-red-600' : 'bg-gray-400') }} text-white font-bold text-sm">
                                                {{ $angsuran->angsuran_ke }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $angsuran->tanggal_jatuh_tempo->format('d/m/Y') }}
                                            </span>
                                            @if($isLate)
                                                @php
                                                    $hariTerlambat = \Carbon\Carbon::now()->diffInDays($angsuran->tanggal_jatuh_tempo);
                                                @endphp
                                                <span class="ml-2 text-xs text-red-600 font-semibold">
                                                    ({{ $hariTerlambat }} hari)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="font-semibold text-gray-900 text-sm">
                                                Rp {{ number_format($angsuran->nominal_angsuran, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($angsuran->tanggal_bayar)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    {{ $angsuran->tanggal_bayar->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if($angsuran->denda > 0)
                                                <span class="font-semibold text-red-600 text-sm">
                                                    Rp {{ number_format($angsuran->denda, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($angsuran->status == 'dibayar')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Lunas
                                                </span>
                                            @elseif($isLate)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    Terlambat
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Jadwal Angsuran</h3>
                    <p class="text-gray-500">Anda belum memiliki pinjaman yang dicairkan atau sedang aktif.</p>
                </div>
            @endforelse

            <!-- Info Card -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-blue-700">
                            Informasi Jadwal Angsuran
                        </p>
                        <ul class="mt-2 text-sm text-blue-600 list-disc list-inside space-y-1">
                            <li>Pastikan Anda membayar angsuran sebelum tanggal jatuh tempo untuk menghindari denda</li>
                            <li>Denda keterlambatan sebesar 1% per hari dari nominal angsuran</li>
                            <li>Hubungi admin koperasi untuk melakukan pembayaran angsuran</li>
                            <li>Status "Terlambat" akan muncul jika melewati tanggal jatuh tempo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
