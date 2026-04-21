@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
                        <p class="text-sm text-gray-600 mt-1">Semua aktivitas keuangan Anda di koperasi</p>
                    </div>
                </div>
            </div>

            @if(!$anggota)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-bold text-yellow-800">Data Anggota Tidak Ditemukan</h3>
                            <p class="text-yellow-700 mt-1">Akun Anda belum terdaftar sebagai anggota. Silakan hubungi admin.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Simpanan -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Total Simpanan</p>
                                <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</h3>
                                <p class="text-green-100 text-xs mt-1">Uang masuk</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-arrow-down text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pinjaman Diterima -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Pinjaman Diterima</p>
                                <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</h3>
                                <p class="text-blue-100 text-xs mt-1">Dana dicairkan</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-hand-holding-usd text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Angsuran Dibayar -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium">Angsuran Dibayar</p>
                                <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalAngsuran, 0, ',', '.') }}</h3>
                                <p class="text-yellow-100 text-xs mt-1">Uang keluar</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-arrow-up text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Transaksi -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Transaksi</p>
                                <h3 class="text-2xl font-bold mt-2">{{ $transaksi->count() }}</h3>
                                <p class="text-purple-100 text-xs mt-1">Semua aktivitas</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-list-alt text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
                    <div class="flex flex-wrap gap-2">
                        <button onclick="filterTransaksi('all')" class="filter-btn active px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                            <i class="fas fa-list mr-2"></i>
                            Semua Transaksi
                        </button>
                        <button onclick="filterTransaksi('Simpanan')" class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                            <i class="fas fa-piggy-bank mr-2"></i>
                            Simpanan
                        </button>
                        <button onclick="filterTransaksi('Pinjaman')" class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                            <i class="fas fa-hand-holding-usd mr-2"></i>
                            Pinjaman
                        </button>
                        <button onclick="filterTransaksi('Angsuran')" class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Angsuran
                        </button>
                    </div>
                </div>

                <!-- Tabel Riwayat Transaksi -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                        <h4 class="text-lg font-bold">
                            <i class="fas fa-history mr-2"></i>
                            Daftar Transaksi
                        </h4>
                    </div>

                    <div class="overflow-x-auto p-6">
                        <table id="transaksiTable" class="w-full">
                            <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Keterangan</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Debit</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Kredit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($transaksi as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 transaksi-row" data-jenis="{{ $item['jenis'] }}">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <div>
                                                <p class="font-semibold">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item['tanggal'])->format('H:i') }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item['jenis'] == 'Simpanan')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-piggy-bank mr-1"></i>
                                                    {{ $item['jenis'] }}
                                                </span>
                                            @elseif($item['jenis'] == 'Pinjaman')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <i class="fas fa-hand-holding-usd mr-1"></i>
                                                    {{ $item['jenis'] }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    {{ $item['jenis'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $item['kategori'] }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item['keterangan'] }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @if($item['debit'] > 0)
                                                <span class="font-bold text-green-600">+ Rp {{ number_format($item['debit'], 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($item['kredit'] > 0)
                                                <span class="font-bold text-red-600">- Rp {{ number_format($item['kredit'], 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">Belum ada riwayat transaksi</p>
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
                                Informasi Riwayat Transaksi
                            </p>
                            <ul class="mt-2 text-sm text-blue-600 list-disc list-inside space-y-1">
                                <li><strong>Debit</strong> = Uang masuk ke saldo Anda (Simpanan, Pencairan Pinjaman)</li>
                                <li><strong>Kredit</strong> = Uang keluar dari saldo Anda (Pembayaran Angsuran)</li>
                                <li>Gunakan filter untuk melihat jenis transaksi tertentu</li>
                                <li>Data ditampilkan dari transaksi terbaru</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        .filter-btn {
            background-color: #e5e7eb;
            color: #4b5563;
        }
        .filter-btn:hover {
            background-color: #d1d5db;
        }
        .filter-btn.active {
            background-color: #3b82f6;
            color: white;
        }
    </style>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        let table;

        $(document).ready(function() {
            table = $('#transaksiTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 25,
                "order": [[1, 'desc']], // Sort by date descending
                "columnDefs": [
                    { "orderable": false, "targets": [0] }
                ]
            });
        });

        function filterTransaksi(jenis) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.filter-btn').classList.add('active');

            // Filter table
            if (jenis === 'all') {
                table.column(2).search('').draw();
            } else {
                table.column(2).search(jenis).draw();
            }
        }
    </script>
@endsection
