@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Data Anggota</h2>
                @if(auth()->user()->hasPermission('anggota.create'))
                    <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Anggota</span>
                    </button>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto p-6">
                    <table id="anggotaTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No Anggota</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">TTL</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">JK</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Pekerjaan</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($anggota as $member)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $member->no_anggota }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-semibold text-sm">{{ strtoupper(substr($member->nama, 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-semibold text-gray-900">{{ $member->nama }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $member->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $member->tempat_lahir ?? '-' }}, {{ $member->tanggal_lahir ? $member->tanggal_lahir->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @if($member->jenis_kelamin)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $member->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                {{ $member->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $member->pekerjaan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($member->statusAnggota)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $member->statusAnggota->nama_status }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                <i class="fas fa-minus-circle mr-1"></i>
                                                Belum ada status
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if(auth()->user()->hasPermission('anggota.read'))
                                                <a href="{{ route('detail-anggota.show', $member->id_anggota) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('anggota.update'))
                                                <button onclick="openEditModal({{ $member->id_anggota }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            @endif
                                            @if(auth()->user()->hasPermission('anggota.delete'))
                                                <form action="{{ route('anggota.destroy', $member->id_anggota) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
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
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data anggota</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Create/Edit Anggota -->
    <div id="anggotaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
                <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">Tambah Anggota</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="anggotaForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" id="anggotaMethod" name="_method" value="POST">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No Anggota <span class="text-red-500">*</span></label>
                            <input type="text" name="no_anggota" id="noAnggota" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Contoh: A001">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Masukkan email">
                            <p class="text-xs text-gray-500 mt-1">Email harus terdaftar di tabel users</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempatLahir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Masukkan tempat lahir">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggalLahir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenisKelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan</label>
                            <input type="text" name="pekerjaan" id="pekerjaan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Masukkan pekerjaan">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Aktif <span class="text-red-500">*</span></label>
                            <select name="aktif" id="aktif" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                                <option value="Y">Aktif</option>
                                <option value="N">Nonaktif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Anggota</label>
                            <select name="id_status_anggota" id="idStatusAnggota" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                                <option value="">Pilih Status Anggota</option>
                                @foreach($statusAnggota as $status)
                                    <option value="{{ $status->id_status_anggota }}">{{ $status->nama_status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" placeholder="Masukkan alamat lengkap"></textarea>
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
            const modal = document.getElementById('anggotaModal');

            document.getElementById('modalTitle').textContent = 'Tambah Anggota';
            document.getElementById('anggotaForm').action = '{{ route("anggota.store") }}';
            document.getElementById('anggotaMethod').value = 'POST';
            document.getElementById('noAnggota').value = '';
            document.getElementById('nama').value = '';
            document.getElementById('email').value = '';
            document.getElementById('tempatLahir').value = '';
            document.getElementById('tanggalLahir').value = '';
            document.getElementById('jenisKelamin').value = '';
            document.getElementById('pekerjaan').value = '';
            document.getElementById('alamat').value = '';
            document.getElementById('aktif').value = 'Y';
            document.getElementById('idStatusAnggota').value = '';

            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }

        async function openEditModal(idAnggota) {
            try {
                const response = await fetch(`/anggota/${idAnggota}`);
                const anggota = await response.json();

                document.getElementById('modalTitle').textContent = 'Edit Anggota';
                document.getElementById('anggotaForm').action = `/anggota/${idAnggota}`;
                document.getElementById('anggotaMethod').value = 'PUT';
                document.getElementById('noAnggota').value = anggota.no_anggota;
                document.getElementById('nama').value = anggota.nama;
                document.getElementById('email').value = anggota.email;
                document.getElementById('tempatLahir').value = anggota.tempat_lahir || '';
                document.getElementById('tanggalLahir').value = anggota.tanggal_lahir || '';
                document.getElementById('jenisKelamin').value = anggota.jenis_kelamin || '';
                document.getElementById('pekerjaan').value = anggota.pekerjaan || '';
                document.getElementById('alamat').value = anggota.alamat || '';
                document.getElementById('aktif').value = anggota.aktif;
                document.getElementById('idStatusAnggota').value = anggota.id_status_anggota || '';

                const modal = document.getElementById('anggotaModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data anggota');
            }
        }

        function closeModal() {
            const modal = document.getElementById('anggotaModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('anggotaModal').addEventListener('click', function(e) {
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
            $('#anggotaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "order": [[0, 'asc']],
                "columnDefs": [
                    { "orderable": false, "targets": 7 } // Disable sorting on action column
                ]
            });
        });
    </script>
@endsection
