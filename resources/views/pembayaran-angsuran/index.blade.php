@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Pembayaran Angsuran</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola pembayaran angsuran pinjaman anggota</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Pinjaman Aktif</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalPinjaman }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-file-invoice-dollar text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Angsuran Belum Bayar</p>
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
                            <p class="text-red-100 text-sm font-medium">Angsuran Terlambat</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalAngsuranTelat }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Pinjaman -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Pinjaman dengan Angsuran Aktif
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="pinjamanTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Anggota</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Pokok Pinjaman</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Lama Angsuran</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Sisa Angsuran</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pinjaman as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($item->anggota->nama, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-semibold text-gray-900">{{ $item->anggota->nama }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->anggota->no_anggota }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-semibold text-gray-900">
                                            Rp {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $item->lama_angsuran }} bulan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($item->sisa_angsuran == 0) bg-green-100 text-green-800
                                            @elseif($item->sisa_angsuran <= 3) bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $item->sisa_angsuran }} / {{ $item->lama_angsuran }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->status_angsuran == 'aktif')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Aktif
                                            </span>
                                        @elseif($item->status_angsuran == 'selesai')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <i class="fas fa-flag-checkered mr-1"></i>
                                                Selesai
                                            </span>
                                        @elseif($item->status_angsuran == 'macet')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Macet
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('pembayaran-angsuran.detail', $item->id_pinjaman) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200 text-xs inline-flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada pinjaman dengan angsuran aktif</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
            $('#pinjamanTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "order": [[5, 'desc']], // Sort by Status
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] }
                ]
            });
        });
    </script>
@endsection
