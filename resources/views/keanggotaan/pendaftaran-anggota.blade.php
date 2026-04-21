@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Pendaftaran Anggota</h2>
                    <p class="text-sm text-gray-600 mt-1">Verifikasi dan aktifkan anggota baru</p>
                </div>
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-users mr-2"></i>
                    {{ $pendaftaran->count() }} Pendaftar Baru
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto p-6">
                    <table id="pendaftaranTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No Anggota</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">TTL</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Pekerjaan</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Tanggal Daftar</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pendaftaran as $member)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $member->no_anggota }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                <span class="text-orange-600 font-semibold text-sm">{{ strtoupper(substr($member->nama, 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-semibold text-gray-900">{{ $member->nama }}</div>
                                                <div class="text-xs text-gray-500">
                                                    @if($member->jenis_kelamin)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $member->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                            {{ $member->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $member->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $member->tempat_lahir ?? '-' }}, {{ $member->tanggal_lahir ? $member->tanggal_lahir->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $member->pekerjaan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-700">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $member->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if(auth()->user()->hasPermission('anggota.update'))
                                                <button onclick="openApproveModal({{ $member->id_anggota }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition-colors duration-200 text-xs font-semibold" title="Aktifkan">
                                                    <i class="fas fa-check-circle"></i> Aktifkan
                                                </button>
                                            @endif
                                            @if(auth()->user()->hasPermission('anggota.delete'))
                                                <form action="{{ route('pendaftaran-anggota.destroy', $member->id_anggota) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak dan menghapus pendaftaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200 text-xs font-semibold" title="Tolak">
                                                        <i class="fas fa-times-circle"></i> Tolak
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-check-circle text-4xl mb-3 text-green-300"></i>
                                        <p class="text-lg font-semibold">Tidak ada pendaftaran baru</p>
                                        <p class="text-sm text-gray-400 mt-1">Semua anggota sudah diverifikasi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Approve Anggota -->
    <div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
                <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">
                        <i class="fas fa-check-circle mr-2"></i>
                        Aktifkan Anggota
                    </h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="approveForm" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 font-semibold">
                                        Detail Anggota
                                    </p>
                                    <div class="mt-2 text-sm text-blue-600">
                                        <p><strong>Nama:</strong> <span id="detailNama">-</span></p>
                                        <p><strong>Email:</strong> <span id="detailEmail">-</span></p>
                                        <p><strong>No Anggota:</strong> <span id="detailNoAnggota">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih Status Anggota <span class="text-red-500">*</span>
                            </label>
                            <select name="id_status_anggota" id="idStatusAnggota" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                                <option value="">Pilih Status Anggota</option>
                                @foreach($statusAnggota as $status)
                                    <option value="{{ $status->id_status_anggota }}">{{ $status->nama_status }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Status ini akan diberikan kepada anggota setelah diaktifkan</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            Aktifkan Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function openApproveModal(idAnggota) {
            try {
                const response = await fetch(`/pendaftaran-anggota/${idAnggota}`);
                const anggota = await response.json();

                document.getElementById('approveForm').action = `/pendaftaran-anggota/${idAnggota}/approve`;
                document.getElementById('detailNama').textContent = anggota.nama;
                document.getElementById('detailEmail').textContent = anggota.email;
                document.getElementById('detailNoAnggota').textContent = anggota.no_anggota;
                document.getElementById('idStatusAnggota').value = '';

                const modal = document.getElementById('approveModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data anggota');
            }
        }

        function closeModal() {
            const modal = document.getElementById('approveModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('approveModal').addEventListener('click', function(e) {
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
            $('#pendaftaranTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "order": [[5, 'desc']], // Sort by tanggal daftar
                "columnDefs": [
                    { "orderable": false, "targets": 6 } // Disable sorting on action column
                ]
            });
        });
    </script>
@endsection
