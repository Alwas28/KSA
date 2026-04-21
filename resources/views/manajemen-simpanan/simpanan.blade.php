@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Data Simpanan</h2>
                @if(auth()->user()->hasPermission('simpanan.create'))
                    <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Simpanan</span>
                    </button>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto p-6">
                    <table id="simpananTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama Anggota</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Jenis Simpanan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($simpanan as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-semibold text-sm">{{ strtoupper(substr($item->anggota->nama ?? 'N/A', 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-semibold text-gray-900">{{ $item->anggota->nama ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->anggota->no_anggota ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $item->jenisSimpanan->nama_jenis ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $item->keterangan ? Str::limit($item->keterangan, 50) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if(auth()->user()->hasPermission('simpanan.update'))
                                                <button onclick="openEditModal({{ $item->id_simpanan }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            @endif
                                            @if(auth()->user()->hasPermission('simpanan.delete'))
                                                <form action="{{ route('simpanan.destroy', $item->id_simpanan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus simpanan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Hapus">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data simpanan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Create/Edit Simpanan -->
    <div id="simpananModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
                <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">Tambah Simpanan</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="simpananForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" id="simpananMethod" name="_method" value="POST">

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Anggota <span class="text-red-500">*</span></label>
                            <select name="id_anggota" id="idAnggota" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent select2-anggota">
                                <option value="">Pilih Anggota</option>
                                @foreach($anggota as $member)
                                    <option value="{{ $member->id_anggota }}">{{ $member->no_anggota }} - {{ $member->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Simpanan <span class="text-red-500">*</span></label>
                            <select name="id_jenis_simpanan" id="idJenisSimpanan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                                <option value="">Pilih Jenis Simpanan</option>
                                @foreach($jenisSimpanan as $jenis)
                                    <option value="{{ $jenis->id_jenis_simpanan }}">{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-gray-600 font-semibold">Rp</span>
                                <input type="text" id="nominalDisplay" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="0">
                                <input type="hidden" name="nominal" id="nominal" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Masukkan keterangan (opsional)"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Format Rupiah
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

        // Input nominal dengan format Rupiah
        document.getElementById('nominalDisplay').addEventListener('keyup', function(e) {
            let value = this.value;
            this.value = formatRupiah(value);
            // Set nilai asli (angka saja) ke hidden input
            document.getElementById('nominal').value = value.replace(/\./g, '');
        });

        function initSelect2() {
            // Destroy existing Select2 if any
            if ($('#idAnggota').hasClass("select2-hidden-accessible")) {
                $('#idAnggota').select2('destroy');
            }

            // Initialize Select2
            $('#idAnggota').select2({
                dropdownParent: $('#simpananModal'),
                placeholder: 'Cari anggota...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Anggota tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });
        }

        function openCreateModal() {
            const modal = document.getElementById('simpananModal');

            document.getElementById('modalTitle').textContent = 'Tambah Simpanan';
            document.getElementById('simpananForm').action = '{{ route("simpanan.store") }}';
            document.getElementById('simpananMethod').value = 'POST';

            // Reset form
            document.getElementById('idJenisSimpanan').value = '';
            document.getElementById('nominal').value = '';
            document.getElementById('nominalDisplay').value = '';
            document.getElementById('keterangan').value = '';

            modal.classList.remove('hidden');
            modal.style.display = 'block';

            // Initialize Select2 after modal is shown
            setTimeout(function() {
                initSelect2();
                $('#idAnggota').val('').trigger('change');
            }, 100);
        }

        async function openEditModal(idSimpanan) {
            try {
                const response = await fetch(`/simpanan/${idSimpanan}`);
                const simpanan = await response.json();

                document.getElementById('modalTitle').textContent = 'Edit Simpanan';
                document.getElementById('simpananForm').action = `/simpanan/${idSimpanan}`;
                document.getElementById('simpananMethod').value = 'PUT';

                document.getElementById('idJenisSimpanan').value = simpanan.id_jenis_simpanan;
                document.getElementById('nominal').value = simpanan.nominal;
                document.getElementById('nominalDisplay').value = formatRupiah(simpanan.nominal.toString());
                document.getElementById('keterangan').value = simpanan.keterangan || '';

                const modal = document.getElementById('simpananModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';

                // Initialize Select2 and set value after modal is shown
                setTimeout(function() {
                    initSelect2();
                    $('#idAnggota').val(simpanan.id_anggota).trigger('change');
                }, 100);
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data simpanan');
            }
        }

        function closeModal() {
            const modal = document.getElementById('simpananModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';

            // Destroy Select2 when closing modal
            if ($('#idAnggota').hasClass("select2-hidden-accessible")) {
                $('#idAnggota').select2('destroy');
            }
        }

        // Close modal when clicking outside
        document.getElementById('simpananModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 styling */
        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            padding-left: 0;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #16a34a;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #16a34a;
            outline: 2px solid #16a34a;
            outline-offset: 2px;
        }
    </style>
@endsection

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#simpananTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "order": [[0, 'asc']],
                "columnDefs": [
                    { "orderable": false, "targets": 6 } // Disable sorting on action column
                ]
            });
        });
    </script>
@endsection
