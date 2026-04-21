@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Arsip File</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola dokumen dan file penting koperasi</p>
                    </div>
                    @if(auth()->user()->hasPermission('simpanan.create'))
                        <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium">
                            <i class="fas fa-upload"></i>
                            <span>Upload File</span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-folder-open mr-2"></i>
                        Daftar Arsip File
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="arsipTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama File</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tipe File</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Ukuran</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Diupload Oleh</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($arsipFiles as $index => $file)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 font-medium">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-alt text-green-600 mr-2"></i>
                                            <span class="font-semibold text-gray-900">{{ $file->nama_file }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ strtoupper($file->file_type ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ number_format($file->file_size / 1024, 2) }} KB</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $file->keterangan ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $file->uploader->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $file->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(auth()->user()->hasPermission('simpanan.read'))
                                                <a href="{{ route('arsip-file.download', $file->id_arsip) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors duration-200 text-xs" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('simpanan.delete'))
                                                <form action="{{ route('arsip-file.destroy', $file->id_arsip) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded transition-colors duration-200 text-xs" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>Belum ada file yang diupload</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Upload File -->
    <div id="addModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-upload text-green-600 mr-3"></i>
                    Upload File
                </h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('arsip-file.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="nama_file" class="block text-sm font-semibold text-gray-700 mb-2">Nama File <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_file" id="nama_file" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                </div>

                <div>
                    <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">Pilih File <span class="text-red-500">*</span></label>
                    <input type="file" name="file" id="file" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file: 10 MB</p>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-upload mr-2"></i>
                        Upload
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
            $('#arsipTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "order": [[6, 'desc']]
            });
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
