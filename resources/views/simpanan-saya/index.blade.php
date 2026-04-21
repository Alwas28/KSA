@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Simpanan Saya</h2>
                        <p class="text-sm text-gray-600 mt-1">Riwayat dan total simpanan Anda</p>
                    </div>
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-wallet mr-2"></i>
                        {{ $simpanan->count() }} Transaksi
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Simpanan Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Simpanan</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-piggy-bank text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Per Jenis Cards -->
                @foreach($totalPerJenis as $item)
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">{{ $item->jenisSimpanan->nama_jenis }}</p>
                                <h3 class="text-xl font-bold text-gray-800 mt-2">Rp {{ number_format($item->total, 0, ',', '.') }}</h3>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-coins text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Riwayat Simpanan Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Transaksi Simpanan
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="simpananTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Jenis Simpanan</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($simpanan as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $item->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $item->jenisSimpanan->nama_jenis }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-green-600">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-semibold">Belum ada riwayat simpanan</p>
                                        <p class="text-sm text-gray-400 mt-1">Transaksi simpanan Anda akan muncul di sini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Card -->
            @if($simpanan->count() > 0)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-blue-700">
                                Informasi Simpanan
                            </p>
                            <p class="mt-1 text-sm text-blue-600">
                                Total simpanan Anda adalah <strong>Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</strong> dari {{ $simpanan->count() }} transaksi.
                                Untuk menambah simpanan, silakan hubungi admin koperasi.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#simpananTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "order": [[1, 'desc']], // Sort by tanggal (descending - terbaru dulu)
                "columnDefs": [
                    { "orderable": false, "targets": 0 } // Disable sorting on No column
                ]
            });
        });
    </script>
@endsection
