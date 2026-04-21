@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Persetujuan & Pencairan Pinjaman</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola persetujuan dan pencairan pinjaman anggota</p>
            </div>

            <!-- Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="switchTab('menunggu')" id="tab-menunggu" class="tab-button border-b-2 border-yellow-500 text-yellow-600 py-4 px-1 text-sm font-medium">
                            <i class="fas fa-clock mr-2"></i>
                            Menunggu Persetujuan ({{ $pinjamanMenunggu->count() }})
                        </button>
                        <button onclick="switchTab('belum-cair')" id="tab-belum-cair" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            Siap Dicairkan ({{ $pinjamanBelumCair->count() }})
                        </button>
                        <button onclick="switchTab('sudah-cair')" id="tab-sudah-cair" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                            <i class="fas fa-check-circle mr-2"></i>
                            Sudah Dicairkan ({{ $pinjamanSudahCair->count() }})
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Content: Menunggu Persetujuan -->
            <div id="content-menunggu" class="tab-content">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-yellow-600 text-white">
                        <h4 class="text-lg font-bold">
                            <i class="fas fa-clock mr-2"></i>
                            Pinjaman Menunggu Persetujuan
                        </h4>
                    </div>

                    <div class="overflow-x-auto p-6">
                        <table id="menungguTable" class="w-full">
                            <thead class="bg-yellow-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal Pengajuan</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Pokok Pinjaman</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Lama Angsuran</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Angsuran/Bulan</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($pinjamanMenunggu as $index => $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $item->tanggal_pengajuan->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-yellow-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($item->anggota->nama, 0, 1)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-semibold text-gray-900">{{ $item->anggota->nama }}</p>
                                                    <p class="text-xs text-gray-500">{{ $item->anggota->no_anggota }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-semibold">Rp {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                {{ $item->lama_angsuran }} bulan
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-semibold text-green-600">Rp {{ number_format($item->angsuran_per_bulan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <button type="button" onclick="openApproveModal({{ $item->id_pinjaman }}, '{{ $item->anggota->nama }}', {{ $item->pokok_pinjaman }}, {{ $item->lama_angsuran }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs mr-1">
                                                <i class="fas fa-check mr-1"></i>
                                                Setujui
                                            </button>
                                            <form action="{{ route('pinjaman.reject', $item->id_pinjaman) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" onclick="return confirm('Yakin ingin menolak pinjaman ini?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Tolak
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">Tidak ada pinjaman yang menunggu persetujuan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Belum Dicairkan -->
            <div id="content-belum-cair" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600 text-white">
                        <h4 class="text-lg font-bold">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            Pinjaman Siap Dicairkan
                        </h4>
                    </div>

                    <div class="overflow-x-auto p-6">
                        <table id="belumCairTable" class="w-full">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal ACC</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Pokok Pinjaman</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Lama Angsuran</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Angsuran/Bulan</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($pinjamanBelumCair as $index => $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $item->tanggal_acc ? $item->tanggal_acc->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($item->anggota->nama, 0, 1)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-semibold text-gray-900">{{ $item->anggota->nama }}</p>
                                                    <p class="text-xs text-gray-500">{{ $item->anggota->no_anggota }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-semibold">Rp {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                {{ $item->lama_angsuran }} bulan
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-semibold text-green-600">Rp {{ number_format($item->angsuran_per_bulan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <button onclick="cairkanPinjaman({{ $item->id_pinjaman }}, '{{ $item->anggota->nama }}', {{ $item->pokok_pinjaman }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                                <i class="fas fa-hand-holding-usd mr-1"></i>
                                                Cairkan
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">Tidak ada pinjaman yang siap dicairkan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Sudah Dicairkan -->
            <div id="content-sudah-cair" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-green-600 text-white">
                        <h4 class="text-lg font-bold">
                            <i class="fas fa-check-circle mr-2"></i>
                            Pinjaman yang Sudah Dicairkan
                        </h4>
                    </div>

                    <div class="overflow-x-auto p-6">
                        <table id="sudahCairTable" class="w-full">
                            <thead class="bg-green-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal Cair</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Pokok Pinjaman</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Lama Angsuran</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Sisa Angsuran</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($pinjamanSudahCair as $index => $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $item->tanggal_pencairan->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($item->anggota->nama, 0, 1)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-semibold text-gray-900">{{ $item->anggota->nama }}</p>
                                                    <p class="text-xs text-gray-500">{{ $item->anggota->no_anggota }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-semibold text-green-600">Rp {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                {{ $item->lama_angsuran }} bulan
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                @if($item->sisa_angsuran == 0) bg-green-100 text-green-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
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
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">Belum ada pinjaman yang dicairkan</p>
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

    <!-- Modal Approve Pinjaman -->
    <div id="approveModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    Setujui Pinjaman
                </h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="approveForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600">Anggota:</p>
                    <p class="text-lg font-bold text-gray-900" id="approveNamaAnggota"></p>
                    <p class="text-sm text-gray-600 mt-2">Pokok Pinjaman:</p>
                    <p class="text-xl font-bold text-green-600" id="approvePokokPinjaman"></p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <p class="text-sm font-semibold text-yellow-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        Lama angsuran yang diajukan: <span id="approveLamaAngsuranAwal" class="font-bold">0</span> bulan
                    </p>
                </div>

                <div>
                    <label for="approveLamaAngsuran" class="block text-sm font-semibold text-gray-700 mb-2">
                        Lama Angsuran yang Disetujui (Bulan) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="lama_angsuran" id="approveLamaAngsuran" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                           onkeyup="hitungAngsuranApprove()">
                    <p class="text-xs text-gray-500 mt-1">Anda dapat mengubah lama angsuran sebelum menyetujui</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-calculator mr-2 text-green-600"></i>
                        Preview Perhitungan
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pokok Pinjaman:</span>
                            <span class="font-semibold" id="approvePreviewPokok">Rp 0</span>
                        </div>
                        <div class="border-t border-gray-300 pt-2 flex justify-between">
                            <span class="font-semibold text-gray-700">Angsuran per Bulan (<span id="approvePreviewLama">0</span> bulan):</span>
                            <span class="font-bold text-green-600" id="approvePreviewAngsuran">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-check mr-2"></i>
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Cairkan Pinjaman -->
    <div id="cairkanModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-hand-holding-usd text-green-600 mr-3"></i>
                    Cairkan Pinjaman
                </h3>
                <button onclick="closeCairkanModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="cairkanForm" class="space-y-4">
                @csrf
                <input type="hidden" id="cairkanIdPinjaman">

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600">Anggota:</p>
                    <p class="text-lg font-bold text-gray-900" id="cairkanNamaAnggota"></p>
                    <p class="text-sm text-gray-600 mt-2">Total Pinjaman:</p>
                    <p class="text-xl font-bold text-green-600" id="cairkanTotalPinjaman"></p>
                </div>

                <div>
                    <label for="cairkanTanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pencairan <span class="text-red-500">*</span></label>
                    <input type="date" id="cairkanTanggal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Tanggal pinjaman dicairkan kepada anggota</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-sm font-semibold text-yellow-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        Perhatian:
                    </p>
                    <p class="text-xs text-yellow-700 mt-1">Setelah dicairkan, jadwal angsuran akan otomatis dibuat dan tidak dapat dibatalkan.</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeCairkanModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-check mr-2"></i>
                        Cairkan
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
            $('#menungguTable').DataTable({
                "language": {"url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"},
                "pageLength": 10,
                "order": [[1, 'desc']]
            });

            $('#belumCairTable').DataTable({
                "language": {"url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"},
                "pageLength": 10,
                "order": [[1, 'desc']]
            });

            $('#sudahCairTable').DataTable({
                "language": {"url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"},
                "pageLength": 10,
                "order": [[1, 'desc']]
            });

            // Set default tanggal ke hari ini
            document.getElementById('cairkanTanggal').value = new Date().toISOString().split('T')[0];
        });

        let currentPokokPinjaman = 0;

        function openApproveModal(idPinjaman, namaAnggota, pokokPinjaman, lamaAngsuran) {
            currentPokokPinjaman = pokokPinjaman;

            document.getElementById('approveForm').action = '/pinjaman/' + idPinjaman + '/approve';
            document.getElementById('approveNamaAnggota').textContent = namaAnggota;
            document.getElementById('approvePokokPinjaman').textContent = 'Rp ' + pokokPinjaman.toLocaleString('id-ID');
            document.getElementById('approveLamaAngsuranAwal').textContent = lamaAngsuran;
            document.getElementById('approveLamaAngsuran').value = lamaAngsuran;

            hitungAngsuranApprove();
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveForm').reset();
        }

        function hitungAngsuranApprove() {
            const lama = parseInt(document.getElementById('approveLamaAngsuran').value) || 0;
            const angsuranPerBulan = lama > 0 ? currentPokokPinjaman / lama : 0;

            document.getElementById('approvePreviewPokok').textContent = 'Rp ' + currentPokokPinjaman.toLocaleString('id-ID');
            document.getElementById('approvePreviewLama').textContent = lama;
            document.getElementById('approvePreviewAngsuran').textContent = 'Rp ' + angsuranPerBulan.toLocaleString('id-ID');
        }

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-yellow-500', 'text-yellow-600', 'border-blue-500', 'text-blue-600', 'border-green-500', 'text-green-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Activate selected button
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500');

            if (tabName === 'menunggu') {
                activeButton.classList.add('border-yellow-500', 'text-yellow-600');
            } else if (tabName === 'belum-cair') {
                activeButton.classList.add('border-blue-500', 'text-blue-600');
            } else if (tabName === 'sudah-cair') {
                activeButton.classList.add('border-green-500', 'text-green-600');
            }
        }

        function cairkanPinjaman(idPinjaman, namaAnggota, totalPinjaman) {
            document.getElementById('cairkanIdPinjaman').value = idPinjaman;
            document.getElementById('cairkanNamaAnggota').textContent = namaAnggota;
            document.getElementById('cairkanTotalPinjaman').textContent = 'Rp ' + totalPinjaman.toLocaleString('id-ID');
            document.getElementById('cairkanModal').classList.remove('hidden');
        }

        function closeCairkanModal() {
            document.getElementById('cairkanModal').classList.add('hidden');
            document.getElementById('cairkanForm').reset();
        }

        $('#cairkanForm').on('submit', function(e) {
            e.preventDefault();

            const idPinjaman = document.getElementById('cairkanIdPinjaman').value;
            const tanggalPencairan = document.getElementById('cairkanTanggal').value;

            $.ajax({
                url: '/persetujuan-pinjaman/' + idPinjaman + '/cairkan',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal_pencairan: tanggalPencairan
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        closeCairkanModal();
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });
    </script>
@endsection
