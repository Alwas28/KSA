@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Detail Pembayaran Angsuran</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola pembayaran angsuran pinjaman</p>
                    </div>
                    <a href="{{ route('pembayaran-angsuran.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Info Pinjaman -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div class="h-24 w-24 rounded-full bg-green-600 flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr($pinjaman->anggota->nama, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $pinjaman->anggota->nama }}</h3>
                        <p class="text-gray-600 mt-1">{{ $pinjaman->anggota->no_anggota }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <p class="text-xs text-gray-500">Pokok Pinjaman</p>
                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($pinjaman->pokok_pinjaman, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Lama Angsuran</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $pinjaman->lama_angsuran }} Bulan</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Angsuran per Bulan</p>
                                <p class="text-sm font-semibold text-blue-600">Rp {{ number_format($pinjaman->angsuran_per_bulan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Status Angsuran</p>
                        @if($pinjaman->status_angsuran == 'aktif')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 mt-2">
                                <i class="fas fa-check-circle mr-2"></i>
                                Aktif
                            </span>
                        @elseif($pinjaman->status_angsuran == 'selesai')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 mt-2">
                                <i class="fas fa-flag-checkered mr-2"></i>
                                Selesai
                            </span>
                        @elseif($pinjaman->status_angsuran == 'macet')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 mt-2">
                                <i class="fas fa-times-circle mr-2"></i>
                                Macet
                            </span>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">Sisa: {{ $pinjaman->sisa_angsuran }} / {{ $pinjaman->lama_angsuran }} bulan</p>
                    </div>
                </div>
            </div>

            <!-- Jadwal Angsuran -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Jadwal Angsuran
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="angsuranTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Angsuran Ke</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal Bayar</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pinjaman->angsuran as $angsuran)
                                @php
                                    $isLate = $angsuran->status == 'belum_bayar' && \Carbon\Carbon::now()->isAfter($angsuran->tanggal_jatuh_tempo);
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $isLate ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-600 text-white font-bold">
                                            {{ $angsuran->angsuran_ke }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $angsuran->tanggal_jatuh_tempo->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-semibold text-gray-900">
                                            Rp {{ number_format($angsuran->nominal_angsuran, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($angsuran->tanggal_bayar)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <i class="fas fa-calendar-check mr-1"></i>
                                                {{ $angsuran->tanggal_bayar->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($angsuran->status == 'dibayar')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Dibayar
                                            </span>
                                        @elseif($isLate)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($angsuran->status != 'dibayar')
                                            <button onclick="bayarAngsuran({{ $angsuran->id_angsuran }}, {{ $angsuran->nominal_angsuran }}, '{{ $angsuran->tanggal_jatuh_tempo->format('Y-m-d') }}', {{ $angsuran->angsuran_ke }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition-colors duration-200 text-xs inline-flex items-center">
                                                <i class="fas fa-money-bill-wave mr-1"></i>
                                                Bayar
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Bayar Angsuran -->
    <div id="bayarModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-money-bill-wave text-green-600 mr-3"></i>
                    Bayar Angsuran
                </h3>
                <button onclick="closeBayarModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="bayarForm" class="space-y-4">
                @csrf
                <input type="hidden" id="bayarIdAngsuran" name="id_angsuran">
                <input type="hidden" id="bayarTanggalJatuhTempo">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Angsuran Ke</label>
                    <input type="text" id="bayarAngsuranKe" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Angsuran</label>
                    <input type="text" id="bayarNominalAngsuran" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                </div>

                <div>
                    <label for="bayarTanggalBayar" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Bayar <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_bayar" id="bayarTanggalBayar" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                </div>

                <div>
                    <label for="bayarNominalDibayar" class="block text-sm font-semibold text-gray-700 mb-2">Nominal Dibayar <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal_dibayar" id="bayarNominalDibayar" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Masukkan nominal yang dibayarkan</p>
                </div>

                <div>
                    <label for="bayarKeterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="bayarKeterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeBayarModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-check mr-2"></i>
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
            $('#angsuranTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 12,
                "order": [[0, 'asc']], // Sort by Angsuran Ke
                "columnDefs": [
                    { "orderable": false, "targets": [5] }
                ]
            });

            // Set default tanggal bayar ke hari ini
            document.getElementById('bayarTanggalBayar').value = new Date().toISOString().split('T')[0];
        });

        function bayarAngsuran(idAngsuran, nominalAngsuran, tanggalJatuhTempo, angsuranKe) {
            document.getElementById('bayarIdAngsuran').value = idAngsuran;
            document.getElementById('bayarTanggalJatuhTempo').value = tanggalJatuhTempo;
            document.getElementById('bayarAngsuranKe').value = 'Angsuran ke-' + angsuranKe;
            document.getElementById('bayarNominalAngsuran').value = 'Rp ' + nominalAngsuran.toLocaleString('id-ID');
            document.getElementById('bayarNominalDibayar').value = nominalAngsuran;

            document.getElementById('bayarModal').classList.remove('hidden');
        }

        function closeBayarModal() {
            document.getElementById('bayarModal').classList.add('hidden');
            document.getElementById('bayarForm').reset();
        }

        $('#bayarForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("pembayaran-angsuran.store") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        closeBayarModal();
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        });
    </script>
@endsection
