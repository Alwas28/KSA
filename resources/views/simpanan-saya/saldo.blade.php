@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Saldo Simpanan Anggota</h2>
                        <p class="text-sm text-gray-600 mt-1">Rekap saldo simpanan seluruh anggota koperasi</p>
                    </div>
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-users mr-2"></i>
                        {{ $totalAnggota }} Anggota Aktif
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Semua Simpanan</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalSemuaSimpanan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-wallet text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Rata-rata per Anggota</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($rataRataSimpanan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Anggota dengan Simpanan</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $saldoSimpanan->filter(fn($item) => $item['total_simpanan'] > 0)->count() }} dari {{ $totalAnggota }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-user-check text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Saldo Simpanan -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-table mr-2"></i>
                        Daftar Saldo Simpanan Anggota
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="saldoTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Anggota</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                @foreach($jenisSimpanan as $jenis)
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">{{ $jenis->nama_jenis }}</th>
                                @endforeach
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Total Saldo</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Transaksi</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($saldoSimpanan as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($item['anggota']->nama, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-semibold text-gray-900">{{ $item['anggota']->nama }}</p>
                                                <p class="text-xs text-gray-500">{{ $item['anggota']->no_anggota }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item['anggota']->statusAnggota)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                {{ $item['anggota']->statusAnggota->nama_status }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    @foreach($jenisSimpanan as $jenis)
                                        <td class="px-6 py-4 text-right text-sm">
                                            @php
                                                $simpananJenis = $item['simpanan_per_jenis']->firstWhere('id_jenis_simpanan', $jenis->id_jenis_simpanan);
                                            @endphp
                                            @if($simpananJenis)
                                                <span class="font-semibold text-gray-900">Rp {{ number_format($simpananJenis->total, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-green-600 text-base">
                                            Rp {{ number_format($item['total_simpanan'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-file-invoice mr-1"></i>
                                            {{ $item['jumlah_transaksi'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('saldo-simpanan.show', $item['anggota']->id_anggota) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200 text-xs inline-flex items-center" title="Lihat Detail">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 5 + $jenisSimpanan->count() }}" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data saldo simpanan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-blue-700">
                            Informasi Saldo Simpanan
                        </p>
                        <ul class="mt-2 text-sm text-blue-600 list-disc list-inside space-y-1">
                            <li>Tabel menampilkan total saldo simpanan setiap anggota berdasarkan jenis simpanan</li>
                            <li>Total saldo adalah akumulasi dari semua jenis simpanan yang dimiliki anggota</li>
                            <li>Klik tombol "Detail" untuk melihat riwayat transaksi simpanan anggota</li>
                            <li>Data hanya menampilkan anggota dengan status aktif</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#saldoTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 25,
                "order": [[{{ 4 + $jenisSimpanan->count() }}, 'desc']], // Sort by Total Saldo (descending)
                "columnDefs": [
                    { "orderable": false, "targets": 0 }, // No column
                    { "orderable": false, "targets": -1 } // Action column
                ]
            });
        });
    </script>
@endsection
