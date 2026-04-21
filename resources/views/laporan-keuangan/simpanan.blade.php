@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Laporan Simpanan Anggota</h2>
                        <p class="text-sm text-gray-600 mt-1">Per Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openFilterModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-calendar"></i><span>Pilih Tanggal</span>
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $totalPerJenis->count() }} gap-6 mb-6">
                @foreach($totalPerJenis as $jenis)
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium">{{ $jenisSimpanan[$jenis->id_jenis_simpanan]->nama_jenis ?? 'Simpanan' }}</p>
                                <p class="text-2xl font-bold mt-2">Rp {{ number_format($jenis->total, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-full">
                                <i class="fas fa-piggy-bank text-2xl"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">GRAND TOTAL</p>
                            <p class="text-2xl font-bold mt-2">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">KOPERASI SIMPAN PINJAM</h3>
                    <h4 class="text-lg font-bold text-gray-800 mb-2 text-center">LAPORAN SIMPANAN ANGGOTA</h4>
                    <p class="text-sm text-gray-600 mb-6 text-center">Per {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-yellow-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama Anggota</th>
                                    @foreach($jenisSimpanan as $jenis)
                                        <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">{{ $jenis->nama_jenis }}</th>
                                    @endforeach
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $no = 1; @endphp
                                @forelse($simpananData as $idAnggota => $simpananAnggota)
                                    @php
                                        $anggota = $simpananAnggota->first()->anggota;
                                        $totalAnggota = 0;
                                        $simpananPerJenisAnggota = $simpananAnggota->groupBy('id_jenis_simpanan');
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $no++ }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $anggota->nama }}</td>
                                        @foreach($jenisSimpanan as $jenis)
                                            @php
                                                $nominal = $simpananPerJenisAnggota->get($jenis->id_jenis_simpanan)?->sum('nominal') ?? 0;
                                                $totalAnggota += $nominal;
                                            @endphp
                                            <td class="px-6 py-4 text-sm text-right text-gray-700">
                                                Rp {{ number_format($nominal, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 text-sm text-right font-bold text-yellow-700">
                                            Rp {{ number_format($totalAnggota, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 3 + $jenisSimpanan->count() }}" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">Tidak ada data simpanan</p>
                                        </td>
                                    </tr>
                                @endforelse

                                @if($simpananData->count() > 0)
                                    <tr class="bg-yellow-100 font-bold border-t-2 border-yellow-600">
                                        <td colspan="2" class="px-6 py-4 text-sm">TOTAL</td>
                                        @foreach($totalPerJenis as $jenis)
                                            <td class="px-6 py-4 text-sm text-right text-yellow-700">
                                                Rp {{ number_format($jenis->total, 0, ',', '.') }}
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 text-sm text-right text-green-700 text-lg">
                                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
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
                    <h3 class="text-xl font-bold">Pilih Tanggal</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 text-2xl"><i class="fas fa-times"></i></button>
                </div>
                <form action="{{ route('laporan-keuangan.simpanan') }}" method="GET" class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Laporan</label>
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
        function openFilterModal() { document.getElementById('filterModal').classList.remove('hidden'); }
        function closeFilterModal() { document.getElementById('filterModal').classList.add('hidden'); }
        document.getElementById('filterModal').addEventListener('click', function(e) { if (e.target === this) closeFilterModal(); });
    </script>
@endsection
