@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Laporan Laba Rugi</h2>
                        <p class="text-sm text-gray-600 mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openFilterModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-filter"></i>
                            <span>Filter Periode</span>
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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Pendapatan</p>
                            <p class="text-2xl font-bold mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-arrow-up text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Total Beban</p>
                            <p class="text-2xl font-bold mt-2">Rp {{ number_format($totalBeban, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-arrow-down text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br {{ $labaBersih >= 0 ? 'from-blue-500 to-blue-600' : 'from-orange-500 to-orange-600' }} rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">{{ $labaBersih >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</p>
                            <p class="text-2xl font-bold mt-2">Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas {{ $labaBersih >= 0 ? 'fa-check-circle' : 'fa-exclamation-triangle' }} text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Detail -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">KOPERASI SIMPAN PINJAM</h3>
                    <h4 class="text-lg font-bold text-gray-800 mb-2 text-center">LAPORAN LABA RUGI</h4>
                    <p class="text-sm text-gray-600 mb-6 text-center">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>

                    <div class="border-t-2 border-gray-300 pt-6">
                        <!-- PENDAPATAN -->
                        <div class="mb-6">
                            <h5 class="text-lg font-bold text-gray-800 mb-4 bg-green-50 p-3 rounded-lg">PENDAPATAN</h5>
                            <div class="pl-6 space-y-2">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-700">Pendapatan Lain-lain</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($pendapatanLain, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 bg-green-100 px-3 rounded-lg mt-3">
                                    <span class="font-bold text-gray-800">Total Pendapatan</span>
                                    <span class="font-bold text-green-700 text-lg">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- BEBAN -->
                        <div class="mb-6">
                            <h5 class="text-lg font-bold text-gray-800 mb-4 bg-red-50 p-3 rounded-lg">BEBAN</h5>
                            <div class="pl-6 space-y-2">
                                @forelse($detailBiaya as $biaya)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-gray-700">{{ $biaya->kategori }}</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($biaya->total, 0, ',', '.') }}</span>
                                    </div>
                                @empty
                                    <div class="text-center text-gray-500 py-4">
                                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                                        <p>Tidak ada beban dalam periode ini</p>
                                    </div>
                                @endforelse
                                <div class="flex justify-between items-center py-3 bg-red-100 px-3 rounded-lg mt-3">
                                    <span class="font-bold text-gray-800">Total Beban</span>
                                    <span class="font-bold text-red-700 text-lg">Rp {{ number_format($totalBeban, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- LABA/RUGI BERSIH -->
                        <div class="border-t-2 border-gray-300 pt-4">
                            <div class="flex justify-between items-center py-4 bg-{{ $labaBersih >= 0 ? 'blue' : 'orange' }}-100 px-6 rounded-lg">
                                <span class="font-bold text-gray-800 text-xl">{{ $labaBersih >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</span>
                                <span class="font-bold text-{{ $labaBersih >= 0 ? 'blue' : 'orange' }}-700 text-2xl">
                                    Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Filter Periode -->
    <div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 class="text-xl font-bold">Filter Periode</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('laporan-keuangan.laba-rugi') }}" method="GET" class="p-6">
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
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-filter mr-2"></i>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openFilterModal() {
            const modal = document.getElementById('filterModal');
            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }

        function closeFilterModal() {
            const modal = document.getElementById('filterModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('filterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFilterModal();
            }
        });
    </script>
@endsection
