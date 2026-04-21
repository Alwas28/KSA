@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Pinjaman Saya</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola dan pantau pinjaman Anda</p>
                    </div>
                    <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium">
                        <i class="fas fa-plus-circle"></i>
                        <span>Ajukan Pinjaman</span>
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Pinjaman</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-hand-holding-usd text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Pinjaman Aktif</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $pinjamanAktif }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Pinjaman Lunas</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $pinjamanLunas }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-check-double text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pinjaman -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-list mr-2"></i>
                        Riwayat Pinjaman
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="pinjamanTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tgl Pengajuan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tgl Disetujui</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Pokok Pinjaman</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Lama</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Angsuran/Bulan</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pinjaman as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $item->tanggal_pengajuan->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if($item->tanggal_acc)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-calendar-check mr-1"></i>
                                                {{ $item->tanggal_acc->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-700">
                                        {{ $item->lama_angsuran }} bulan
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-semibold text-green-600">
                                        Rp {{ number_format($item->angsuran_per_bulan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->status === 'diajukan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Diajukan
                                            </span>
                                        @elseif($item->status === 'disetujui')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Disetujui
                                            </span>
                                        @elseif($item->status === 'ditolak')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Ditolak
                                            </span>
                                        @elseif($item->status === 'lunas')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                <i class="fas fa-check-double mr-1"></i>
                                                Lunas
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-semibold">Belum ada riwayat pinjaman</p>
                                        <p class="text-sm text-gray-400 mt-1">Klik tombol "Ajukan Pinjaman" untuk mengajukan pinjaman baru</p>
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
                            Informasi Pinjaman
                        </p>
                        <ul class="mt-2 text-sm text-blue-600 list-disc list-inside space-y-1">
                            <li>Pengajuan pinjaman akan diproses oleh admin dalam waktu 1-3 hari kerja</li>
                            <li>Angsuran per bulan adalah hasil bagi pokok pinjaman dengan lama angsuran</li>
                            <li>Status pinjaman dapat berubah menjadi: Diajukan, Disetujui, Ditolak, atau Lunas</li>
                            <li>Koperasi ini beroperasi tanpa sistem bunga (syariah)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Ajukan Pinjaman -->
    <div id="addModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="text-xl font-bold">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                    Ajukan Pinjaman Baru
                </h3>
                <button onclick="closeAddModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('pinjaman-saya.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pengajuan pinjaman Anda akan diproses oleh admin. Pastikan data yang Anda masukkan sudah benar.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pokok Pinjaman (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="pokok_pinjaman" id="pokokPinjaman" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" onkeyup="hitungTotal()">
                        <p class="text-xs text-gray-500 mt-1">Masukkan jumlah pinjaman yang Anda ajukan</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lama Angsuran (Bulan) <span class="text-red-500">*</span></label>
                        <input type="number" name="lama_angsuran" id="lamaAngsuran" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" onkeyup="hitungTotal()">
                        <p class="text-xs text-gray-500 mt-1">Jangka waktu pelunasan dalam bulan</p>
                    </div>

                    <!-- Kalkulasi Preview -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calculator mr-2 text-green-600"></i>
                            Rincian Perhitungan
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pokok Pinjaman:</span>
                                <span class="font-semibold" id="previewPokok">Rp 0</span>
                            </div>
                            <div class="border-t border-gray-300 pt-2 flex justify-between">
                                <span class="font-semibold text-gray-700">Lama Angsuran:</span>
                                <span class="font-bold text-blue-600"><span id="previewLama">0</span> bulan</span>
                            </div>
                            <div class="border-t border-gray-300 pt-2 flex justify-between">
                                <span class="font-semibold text-gray-700">Angsuran per Bulan:</span>
                                <span class="font-bold text-green-600" id="previewAngsuran">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan Pinjaman
                    </button>
                </div>
            </form>
        </div>
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
                "order": [[1, 'desc']]
            });
        });

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function hitungTotal() {
            const pokok = parseFloat(document.getElementById('pokokPinjaman').value) || 0;
            const lama = parseInt(document.getElementById('lamaAngsuran').value) || 0;

            const angsuranPerBulan = lama > 0 ? pokok / lama : 0;

            document.getElementById('previewPokok').textContent = 'Rp ' + pokok.toLocaleString('id-ID');
            document.getElementById('previewLama').textContent = lama;
            document.getElementById('previewAngsuran').textContent = 'Rp ' + angsuranPerBulan.toLocaleString('id-ID');
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) {
                closeAddModal();
            }
        }
    </script>
@endsection
