@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Laporan Neraca</h2>
                        <p class="text-sm text-gray-600 mt-1">Per Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openFilterModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-calendar"></i>
                            <span>Pilih Tanggal</span>
                        </button>
                        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-print"></i>
                            <span>Cetak</span>
                        </button>
                        <a href="{{ route('laporan-keuangan.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">KOPERASI SIMPAN PINJAM</h3>
                    <h4 class="text-lg font-bold text-gray-800 mb-2 text-center">NERACA</h4>
                    <p class="text-sm text-gray-600 mb-6 text-center">Per {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-2 border-blue-200 rounded-lg p-6">
                            <h5 class="text-lg font-bold text-gray-800 mb-4 bg-blue-50 p-3 rounded-lg text-center">AKTIVA</h5>
                            <div class="mb-4">
                                <p class="font-semibold text-gray-700 mb-3">Aset Lancar</p>
                                <div class="pl-4 space-y-2">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-gray-600">Kas</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($saldoKas, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-gray-600">Piutang Anggota</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($piutang, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t-2 border-blue-300 pt-4 mt-4">
                                <div class="flex justify-between items-center py-3 bg-blue-100 px-4 rounded-lg">
                                    <span class="font-bold text-gray-800 text-lg">TOTAL AKTIVA</span>
                                    <span class="font-bold text-blue-700 text-xl">Rp {{ number_format($totalAktiva, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-2 border-green-200 rounded-lg p-6">
                            <h5 class="text-lg font-bold text-gray-800 mb-4 bg-green-50 p-3 rounded-lg text-center">PASIVA</h5>
                            <div class="mb-4">
                                <p class="font-semibold text-gray-700 mb-3">Kewajiban</p>
                                <div class="pl-4 space-y-2">
                                    @foreach($simpananPerJenis as $simpanan)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                            <span class="text-gray-600">{{ $jenisSimpanan[$simpanan->id_jenis_simpanan]->nama_jenis ?? 'Simpanan' }}</span>
                                            <span class="font-semibold text-gray-800">Rp {{ number_format($simpanan->total, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between items-center py-2 bg-gray-50 px-2 rounded mt-2">
                                        <span class="font-semibold text-gray-700">Total Simpanan</span>
                                        <span class="font-bold text-gray-800">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <p class="font-semibold text-gray-700 mb-3">Modal</p>
                                <div class="pl-4 space-y-2">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-gray-600">SHU Tahun Berjalan</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($shuTahunBerjalan, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t-2 border-green-300 pt-4 mt-4">
                                <div class="flex justify-between items-center py-3 bg-green-100 px-4 rounded-lg">
                                    <span class="font-bold text-gray-800 text-lg">TOTAL PASIVA</span>
                                    <span class="font-bold text-green-700 text-xl">Rp {{ number_format($totalPasiva, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded-lg {{ abs($totalAktiva - $totalPasiva) < 0.01 ? 'bg-green-50 border border-green-300' : 'bg-red-50 border border-red-300' }}">
                        <div class="flex items-center justify-center gap-2">
                            @if(abs($totalAktiva - $totalPasiva) < 0.01)
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                <span class="font-semibold text-green-700">Neraca Seimbang (Balance)</span>
                            @else
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                <span class="font-semibold text-red-700">Neraca Tidak Seimbang - Selisih: Rp {{ number_format(abs($totalAktiva - $totalPasiva), 0, ',', '.') }}</span>
                            @endif
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
                    <h3 class="text-xl font-bold">Pilih Tanggal Neraca</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('laporan-keuangan.neraca') }}" method="GET" class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Neraca</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeFilterModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-check mr-2"></i>Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openFilterModal() {
            document.getElementById('filterModal').classList.remove('hidden');
        }
        function closeFilterModal() {
            document.getElementById('filterModal').classList.add('hidden');
        }
        document.getElementById('filterModal').addEventListener('click', function(e) {
            if (e.target === this) closeFilterModal();
        });
    </script>
@endsection
