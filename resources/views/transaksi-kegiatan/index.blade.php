@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Transaksi Kegiatan</h2>
                        <p class="text-sm text-gray-600 mt-1">Catat transaksi pemasukan dan pengeluaran kegiatan usaha</p>
                    </div>
                    @if(auth()->user()->hasPermission('simpanan.create'))
                        <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium">
                            <i class="fas fa-plus-circle"></i>
                            <span>Tambah Transaksi</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Info Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                @foreach($kegiatanAktif as $kegiatan)
                    @php
                        $pemasukan = $kegiatan->transaksi()->where('jenis_transaksi', 'pemasukan')->sum('nominal');
                        $pengeluaran = $kegiatan->transaksi()->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
                        $saldo = $pemasukan - $pengeluaran;
                    @endphp
                    <a href="{{ route('transaksi-kegiatan.detail', $kegiatan->id_kegiatan) }}" class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition">
                        <h4 class="font-bold text-gray-800 mb-2">{{ $kegiatan->nama_kegiatan }}</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>Pemasukan: <span class="font-semibold text-green-600">Rp {{ number_format($pemasukan, 0, ',', '.') }}</span></p>
                            <p>Pengeluaran: <span class="font-semibold text-red-600">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span></p>
                            <p class="pt-1 border-t">Saldo: <span class="font-bold text-blue-600">Rp {{ number_format($saldo, 0, ',', '.') }}</span></p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-list mr-2"></i>
                        Riwayat Transaksi
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="transaksiTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kegiatan</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($transaksi as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 font-medium">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $item->tanggal_transaksi->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-gray-900">{{ $item->kegiatan->nama_kegiatan }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->jenis_transaksi == 'pemasukan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-arrow-up mr-1"></i>
                                                Pemasukan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-arrow-down mr-1"></i>
                                                Pengeluaran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-semibold {{ $item->jenis_transaksi == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $item->keterangan }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $item->creator->name }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>Belum ada transaksi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div id="addModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                    Tambah Transaksi
                </h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('transaksi-kegiatan.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="id_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">Kegiatan <span class="text-red-500">*</span></label>
                    <select name="id_kegiatan" id="id_kegiatan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                        <option value="">Pilih Kegiatan</option>
                        @foreach($kegiatanAktif as $kegiatan)
                            <option value="{{ $kegiatan->id_kegiatan }}">{{ $kegiatan->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tanggal_transaksi" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                </div>

                <div>
                    <label for="jenis_transaksi" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Transaksi <span class="text-red-500">*</span></label>
                    <select name="jenis_transaksi" id="jenis_transaksi" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                        <option value="">Pilih Jenis</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <div>
                    <label for="nominal" class="block text-sm font-semibold text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal" id="nominal" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan <span class="text-red-500">*</span></label>
                    <textarea name="keterangan" id="keterangan" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
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
            $('#transaksiTable').DataTables({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "order": [[1, 'desc']]
            });

            document.getElementById('tanggal_transaksi').value = new Date().toISOString().split('T')[0];
        });

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            if (event.target == addModal) {
                closeAddModal();
            }
        }
    </script>
@endsection
