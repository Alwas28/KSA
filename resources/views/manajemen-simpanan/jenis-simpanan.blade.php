@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Data Jenis Simpanan</h2>
                @if(auth()->user()->hasPermission('jenis_simpanan.create'))
                    <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Jenis Simpanan</span>
                    </button>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto p-6">
                    <table id="jenisSimpananTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama Jenis Simpanan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($jenisSimpanan as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-semibold text-sm">{{ strtoupper(substr($item->nama_jenis, 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-semibold text-gray-900">{{ $item->nama_jenis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $item->deskripsi ? Str::limit($item->deskripsi, 100) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if(auth()->user()->hasPermission('jenis_simpanan.update'))
                                                <button onclick="openEditModal({{ $item->id_jenis_simpanan }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            @endif
                                            @if(auth()->user()->hasPermission('jenis_simpanan.delete'))
                                                <form action="{{ route('jenis-simpanan.destroy', $item->id_jenis_simpanan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis simpanan ini?')">
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
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data jenis simpanan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Create/Edit Jenis Simpanan -->
    <div id="jenisSimpananModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
                <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">Tambah Jenis Simpanan</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="jenisSimpananForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" id="jenisSimpananMethod" name="_method" value="POST">

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Jenis Simpanan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_jenis" id="namaJenis" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Contoh: Simpanan Wajib">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Masukkan deskripsi jenis simpanan (opsional)"></textarea>
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
        function openCreateModal() {
            const modal = document.getElementById('jenisSimpananModal');

            document.getElementById('modalTitle').textContent = 'Tambah Jenis Simpanan';
            document.getElementById('jenisSimpananForm').action = '{{ route("jenis-simpanan.store") }}';
            document.getElementById('jenisSimpananMethod').value = 'POST';
            document.getElementById('namaJenis').value = '';
            document.getElementById('deskripsi').value = '';

            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }

        async function openEditModal(idJenisSimpanan) {
            try {
                const response = await fetch(`/jenis-simpanan/${idJenisSimpanan}`);
                const jenisSimpanan = await response.json();

                document.getElementById('modalTitle').textContent = 'Edit Jenis Simpanan';
                document.getElementById('jenisSimpananForm').action = `/jenis-simpanan/${idJenisSimpanan}`;
                document.getElementById('jenisSimpananMethod').value = 'PUT';
                document.getElementById('namaJenis').value = jenisSimpanan.nama_jenis;
                document.getElementById('deskripsi').value = jenisSimpanan.deskripsi || '';

                const modal = document.getElementById('jenisSimpananModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data jenis simpanan');
            }
        }

        function closeModal() {
            const modal = document.getElementById('jenisSimpananModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('jenisSimpananModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#jenisSimpananTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "order": [[0, 'asc']],
                "columnDefs": [
                    { "orderable": false, "targets": 3 } // Disable sorting on action column
                ]
            });
        });
    </script>
@endsection
