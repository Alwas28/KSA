@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Laporan Pinjaman & Angsuran</h2>
                        <p class="text-sm text-gray-600 mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openFilterModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-filter"></i><span>Filter Periode</span>
                        </button>
                        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-print"></i><span>Cetak</span>
                        </button>
                        <a href="{{ route('laporan-keuangan.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-arrow-left"></i><span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Pinjaman Diajukan</p>
                            <p class="text-2xl font-bold mt-2">{{ $rekapPinjaman->get('diajukan')->jumlah ?? 0 }}</p>
                            <p class="text-sm mt-1">Rp {{ number_format($rekapPinjaman->get('diajukan')->total ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-file-alt text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Pinjaman Disetujui</p>
                            <p class="text-2xl font-bold mt-2">{{ $rekapPinjaman->get('disetujui')->jumlah ?? 0 }}</p>
                            <p class="text-sm mt-1">Rp {{ number_format($rekapPinjaman->get('disetujui')->total ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Pinjaman Dicairkan</p>
                            <p class="text-2xl font-bold mt-2">{{ $rekapPinjaman->get('dicairkan')->jumlah ?? 0 }}</p>
                            <p class="text-sm mt-1">Rp {{ number_format($rekapPinjaman->get('dicairkan')->total ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Pinjaman Lunas</p>
                            <p class="text-2xl font-bold mt-2">{{ $rekapPinjaman->get('lunas')->jumlah ?? 0 }}</p>
                            <p class="text-sm mt-1">Rp {{ number_format($rekapPinjaman->get('lunas')->total ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-check-double text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Rekap Angsuran</h3>
                        <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                            <span class="text-gray-600">Total Angsuran</span>
                            <span class="font-bold text-gray-800">{{ $totalAngsuran }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                            <span class="text-gray-600">Sudah Dibayar</span>
                            <span class="font-bold text-green-600">{{ $angsuranDibayar }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Belum Dibayar</span>
                            <span class="font-bold text-red-600">{{ $angsuranBelumBayar }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Nominal Dibayar</h3>
                        <i class="fas fa-money-check-alt text-green-600 text-2xl"></i>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalNominalDibayar, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600 mt-2">Total pembayaran angsuran</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Total Denda</h3>
                        <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600 mt-2">Total denda keterlambatan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Pinjaman</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama Anggota</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Pokok Pinjaman</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Lama (Bulan)</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Angsuran/Bulan</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Sisa Angsuran</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($detailPinjaman as $index => $pinjaman)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($pinjaman->created_at)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $pinjaman->anggota->nama }}</td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-700">Rp {{ number_format($pinjaman->pokok_pinjaman, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-700">{{ $pinjaman->lama_angsuran }} bulan</td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-700">Rp {{ number_format($pinjaman->angsuran_per_bulan ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-right">
                                            <div class="font-semibold text-red-600">Rp {{ number_format($pinjaman->nominal_sisa_angsuran ?? 0, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500">({{ $pinjaman->sisa_angsuran ?? 0 }} bulan tersisa)</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($pinjaman->status == 'diajukan')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Diajukan</span>
                                            @elseif($pinjaman->status == 'disetujui')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Disetujui</span>
                                            @elseif($pinjaman->status == 'dicairkan')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Dicairkan</span>
                                            @elseif($pinjaman->status == 'lunas')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">Lunas</span>
                                            @else
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">Tidak ada data pinjaman dalam periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 class="text-xl font-bold">Filter Periode</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 text-2xl"><i class="fas fa-times"></i></button>
                </div>
                <form action="{{ route('laporan-keuangan.pinjaman') }}" method="GET" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeFilterModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-filter mr-2"></i>Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openFilterModal() { document.getElementById('filterModal').classList.remove('hidden'); }
        function closeFilterModal() { document.getElementById('filterModal').classList.add('hidden'); }
        document.getElementById('filterModal').addEventListener('click', function(e) { if (e.target === this) closeFilterModal(); });
    </script>
@endsection
