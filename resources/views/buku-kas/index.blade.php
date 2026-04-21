@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Buku Kas</h2>
                        <p class="text-sm text-gray-600 mt-1">Catatan keluar masuk kas koperasi</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openCreateModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-plus-circle"></i>
                            <span>Tambah Transaksi</span>
                        </button>
                        <button onclick="openFilterModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-filter"></i>
                            <span>Filter Periode</span>
                        </button>
                        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-print"></i>
                            <span>Cetak</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-blue-500 text-xl mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-700">Periode Transaksi</p>
                            <p class="text-sm text-blue-600">
                                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Saldo Awal -->
                <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-100 text-sm font-medium">Saldo Awal</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($saldoAwalValue, 0, ',', '.') }}</h3>
                            <p class="text-gray-100 text-xs mt-1">Periode sebelumnya</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-wallet text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Debit (Uang Masuk) -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Debit</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalDebit, 0, ',', '.') }}</h3>
                            <p class="text-green-100 text-xs mt-1">Uang masuk</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-arrow-down text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Kredit (Uang Keluar) -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Total Kredit</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalKredit, 0, ',', '.') }}</h3>
                            <p class="text-red-100 text-xs mt-1">Uang keluar</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-arrow-up text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Saldo Akhir -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Saldo Akhir</p>
                            <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                            <p class="text-purple-100 text-xs mt-1">Per {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-coins text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Buku Kas -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-book mr-2"></i>
                        Catatan Buku Kas
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="bukuKasTable" class="w-full">
                        <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Debit (Masuk)</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Kredit (Keluar)</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Saldo</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Saldo Awal Row -->
                            <tr class="bg-gray-100 font-semibold no-sort">
                                <td class="px-6 py-4 text-sm text-gray-700">-</td>
                                <td class="px-6 py-4 text-sm text-gray-700" colspan="3">
                                    <strong>Saldo Awal Periode</strong>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">-</td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">-</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <span class="font-bold text-purple-600">Rp {{ number_format($saldoAwalValue, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">-</td>
                            </tr>

                            @forelse($transaksiWithSaldo as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div>
                                            <p class="font-semibold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->tipe == 'debit')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-arrow-down mr-1"></i>
                                                {{ $item->kategori }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-arrow-up mr-1"></i>
                                                {{ $item->kategori }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->keterangan }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($item->tipe == 'debit')
                                            <span class="font-bold text-green-600">+ Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($item->tipe == 'kredit')
                                            <span class="font-bold text-red-600">- Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold {{ $item->saldo >= 0 ? 'text-purple-600' : 'text-red-600' }}">
                                            Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button onclick="openEditModal({{ $item->id_transaksi_umum }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors duration-200" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('buku-kas.destroy', $item->id_transaksi_umum) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Tidak ada transaksi dalam periode ini</p>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Saldo Akhir Row -->
                            @if($transaksiWithSaldo->count() > 0)
                                <tr class="bg-purple-100 font-bold border-t-2 border-purple-600 no-sort">
                                    <td class="px-6 py-4 text-sm text-gray-700">-</td>
                                    <td class="px-6 py-4 text-sm text-gray-700" colspan="3">
                                        <strong>Saldo Akhir Periode</strong>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <span class="text-green-600">Rp {{ number_format($totalDebit, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <span class="text-red-600">Rp {{ number_format($totalKredit, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <span class="text-purple-600">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">-</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ringkasan per Kategori -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-blue-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Ringkasan per Kategori
                    </h4>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($transaksiPerKategori as $kategori => $items)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h5 class="font-bold text-gray-800 mb-3">{{ $kategori }}</h5>
                                <div class="space-y-2">
                                    @foreach($items as $item)
                                        <div class="flex justify-between items-center">
                                            @if($item->tipe == 'debit')
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-arrow-down text-green-500 mr-2"></i>Debit
                                                </span>
                                                <span class="font-semibold text-green-600">
                                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-arrow-up text-red-500 mr-2"></i>Kredit
                                                </span>
                                                <span class="font-semibold text-red-600">
                                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center text-gray-500 py-8">
                                <i class="fas fa-info-circle text-3xl mb-2"></i>
                                <p>Tidak ada data ringkasan</p>
                            </div>
                        @endforelse
                    </div>
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
                            Informasi Buku Kas
                        </p>
                        <ul class="mt-2 text-sm text-blue-600 list-disc list-inside space-y-1">
                            <li><strong>Debit</strong> = Uang masuk (Simpanan, Pembayaran Angsuran)</li>
                            <li><strong>Kredit</strong> = Uang keluar (Pencairan Pinjaman)</li>
                            <li><strong>Saldo</strong> = Saldo berjalan setelah setiap transaksi</li>
                            <li>Gunakan filter periode untuk melihat transaksi pada rentang waktu tertentu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Filter Periode -->
    <div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all">
                <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 class="text-xl font-bold">Filter Periode</h3>
                    <button onclick="closeFilterModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('buku-kas.index') }}" method="GET" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
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

    <!-- Modal Create/Edit Transaksi -->
    <div id="transaksiModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full transform transition-all">
                <div class="bg-purple-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">Tambah Transaksi</h3>
                    <button onclick="closeTransaksiModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="transaksiForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" id="transaksiMethod" name="_method" value="POST">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" id="transaksiTanggal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                            <select name="tipe" id="transaksiTipe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="debit">Debit (Uang Masuk)</option>
                                <option value="kredit">Kredit (Uang Keluar)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <input type="text" name="kategori" id="transaksiKategori" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Contoh: Biaya Operasional">
                            <p class="text-xs text-gray-500 mt-1">Kategori transaksi (mis: Biaya Operasional, Pendapatan Jasa)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                            <input type="text" id="transaksiNominalDisplay" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="0">
                            <input type="hidden" name="nominal" id="transaksiNominal">
                            <p class="text-xs text-gray-500 mt-1">Format: 1.000.000 (gunakan titik sebagai pemisah ribuan)</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan <span class="text-red-500">*</span></label>
                        <textarea name="keterangan" id="transaksiKeterangan" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Keterangan detail transaksi"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeTransaksiModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Format number with thousand separator
        function formatRupiah(angka) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        // Parse formatted number to plain number
        function parseRupiah(rupiah) {
            return parseFloat(rupiah.replace(/\./g, '').replace(/,/g, '.')) || 0;
        }

        // Event listener for nominal input
        document.addEventListener('DOMContentLoaded', function() {
            const nominalDisplay = document.getElementById('transaksiNominalDisplay');
            const nominalHidden = document.getElementById('transaksiNominal');

            if (nominalDisplay) {
                nominalDisplay.addEventListener('keyup', function(e) {
                    let value = this.value;
                    this.value = formatRupiah(value);
                    nominalHidden.value = parseRupiah(this.value);
                });

                nominalDisplay.addEventListener('blur', function() {
                    nominalHidden.value = parseRupiah(this.value);
                });
            }
        });

        function openCreateModal() {
            const modal = document.getElementById('transaksiModal');
            const today = new Date().toISOString().split('T')[0];

            document.getElementById('modalTitle').textContent = 'Tambah Transaksi';
            document.getElementById('transaksiForm').action = '{{ route("buku-kas.store") }}';
            document.getElementById('transaksiMethod').value = 'POST';
            document.getElementById('transaksiTanggal').value = today;
            document.getElementById('transaksiTipe').value = '';
            document.getElementById('transaksiKategori').value = '';
            document.getElementById('transaksiNominalDisplay').value = '';
            document.getElementById('transaksiNominal').value = '';
            document.getElementById('transaksiKeterangan').value = '';

            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }

        async function openEditModal(id) {
            try {
                const response = await fetch(`/buku-kas/${id}`);
                const transaksi = await response.json();

                document.getElementById('modalTitle').textContent = 'Edit Transaksi';
                document.getElementById('transaksiForm').action = `/buku-kas/${id}`;
                document.getElementById('transaksiMethod').value = 'PUT';
                document.getElementById('transaksiTanggal').value = transaksi.tanggal;
                document.getElementById('transaksiTipe').value = transaksi.tipe;
                document.getElementById('transaksiKategori').value = transaksi.kategori;

                // Format nominal with thousand separator
                const nominalFormatted = formatRupiah(transaksi.nominal.toString());
                document.getElementById('transaksiNominalDisplay').value = nominalFormatted;
                document.getElementById('transaksiNominal').value = transaksi.nominal;

                document.getElementById('transaksiKeterangan').value = transaksi.keterangan;

                const modal = document.getElementById('transaksiModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data transaksi');
            }
        }

        function closeTransaksiModal() {
            const modal = document.getElementById('transaksiModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('transaksiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransaksiModal();
            }
        });

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

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
        }
    </style>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#bukuKasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 50,
                "order": [[1, 'asc']], // Sort by date ascending
                "columnDefs": [
                    { "orderable": false, "targets": [0, 7] } // No & Aksi columns
                ]
            });
        });
    </script>
@endsection
