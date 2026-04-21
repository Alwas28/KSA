@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Detail Saldo Simpanan</h2>
                        <p class="text-sm text-gray-600 mt-1">Rincian simpanan: {{ $anggota->nama }}</p>
                    </div>
                    <a href="{{ route('saldo-simpanan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div class="h-24 w-24 rounded-full bg-green-600 flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $anggota->nama }}</h3>
                        <p class="text-gray-600 mt-1">{{ $anggota->no_anggota }}</p>
                        <div class="flex gap-4 mt-3">
                            @if($anggota->statusAnggota)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ $anggota->statusAnggota->nama_status }}
                                </span>
                            @endif
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $anggota->email }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Simpanan</p>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $simpanan->count() }} transaksi</p>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-{{ $totalPerJenis->count() }} gap-6 mb-6">
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

            <!-- Riwayat Transaksi -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-history mr-2"></i>
                        Riwayat Transaksi Simpanan
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="transaksiTable" class="w-full">
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
                                        <p class="text-lg font-semibold">Belum ada riwayat transaksi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($simpanan->count() > 0)
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">Total:</td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600 text-lg">
                                        Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
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
            $('#transaksiTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "order": [[1, 'desc']], // Sort by Tanggal (descending)
                "columnDefs": [
                    { "orderable": false, "targets": 0 } // No column
                ]
            });
        });
    </script>
@endsection
