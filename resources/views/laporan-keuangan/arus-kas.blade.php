@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Laporan Arus Kas</h2>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Saldo Kas Awal</p>
                            <p class="text-2xl font-bold mt-2">Rp {{ number_format($saldoKasAwal, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-wallet text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Arus Kas Bersih</p>
                            <p class="text-2xl font-bold mt-2">Rp {{ number_format($arusKasBersih, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-exchange-alt text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Saldo Kas Akhir</p>
                            <p class="text-2xl font-bold mt-2">Rp {{ number_format($saldoKasAkhir, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-money-check-alt text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">KOPERASI SIMPAN PINJAM</h3>
                    <h4 class="text-lg font-bold text-gray-800 mb-2 text-center">LAPORAN ARUS KAS</h4>
                    <p class="text-sm text-gray-600 mb-6 text-center">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>

                    <div class="border-t-2 border-gray-300 pt-6">
                        <div class="mb-6">
                            <div class="flex justify-between items-center py-3 bg-gray-100 px-4 rounded-lg mb-4">
                                <span class="font-bold text-gray-800">Saldo Kas Awal Periode</span>
                                <span class="font-bold text-gray-800 text-lg">Rp {{ number_format($saldoKasAwal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h5 class="text-lg font-bold text-gray-800 mb-4 bg-green-50 p-3 rounded-lg">ARUS KAS MASUK (PENERIMAAN)</h5>
                            <div class="pl-6 space-y-2">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-700">Penerimaan Simpanan</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($penerimaanSimpanan, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-700">Penerimaan Pembayaran Angsuran</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($penerimaanAngsuran, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-700">Penerimaan Lain-lain</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($penerimaanLain, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 bg-green-100 px-3 rounded-lg mt-3">
                                    <span class="font-bold text-gray-800">Total Penerimaan</span>
                                    <span class="font-bold text-green-700 text-lg">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h5 class="text-lg font-bold text-gray-800 mb-4 bg-red-50 p-3 rounded-lg">ARUS KAS KELUAR (PENGELUARAN)</h5>
                            <div class="pl-6 space-y-2">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-700">Pencairan Pinjaman</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($pengeluaranPinjaman, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-700">Biaya Operasional</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($pengeluaranOperasional, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 bg-red-100 px-3 rounded-lg mt-3">
                                    <span class="font-bold text-gray-800">Total Pengeluaran</span>
                                    <span class="font-bold text-red-700 text-lg">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-t-2 border-gray-300 pt-4">
                            <div class="flex justify-between items-center py-3 bg-purple-100 px-4 rounded-lg mb-3">
                                <span class="font-bold text-gray-800 text-lg">ARUS KAS BERSIH</span>
                                <span class="font-bold text-purple-700 text-xl">Rp {{ number_format($arusKasBersih, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-4 bg-blue-100 px-4 rounded-lg">
                                <span class="font-bold text-gray-800 text-xl">SALDO KAS AKHIR PERIODE</span>
                                <span class="font-bold text-blue-700 text-2xl">Rp {{ number_format($saldoKasAkhir, 0, ',', '.') }}</span>
                            </div>
                        </div>
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
                <form action="{{ route('laporan-keuangan.arus-kas') }}" method="GET" class="p-6">
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
