@extends('layouts.users')

@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
    @include('components.navbar')
    @include('components.toast')

    <main class="content-area flex-1 overflow-y-auto p-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen SHU</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola Sisa Hasil Usaha per anggota per tahun</p>
            </div>
            <button onclick="openCreateModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-plus-circle"></i> Tambah SHU
            </button>
        </div>

        {{-- Filter Tahun --}}
        <form method="GET" action="{{ route('shu.index') }}" class="mb-4 flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Filter Tahun:</label>
            <select name="tahun" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="">-- Semua Tahun --</option>
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahunFilter == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            @if($tahunFilter)
                <a href="{{ route('shu.index') }}" class="text-sm text-gray-500 hover:text-red-500">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>

        {{-- Tabel Data SHU --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-green-600 text-white">
                <h4 class="font-bold text-lg"><i class="fas fa-chart-pie mr-2"></i>Data SHU Anggota</h4>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">No Anggota</th>
                            <th class="px-6 py-3 text-left">Nama Anggota</th>
                            <th class="px-6 py-3 text-center">Tahun</th>
                            <th class="px-6 py-3 text-right">Jumlah SHU</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $i => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-500">{{ $data->firstItem() + $i }}</td>
                                <td class="px-6 py-3 font-medium text-gray-700">{{ $item->anggota->no_anggota ?? '-' }}</td>
                                <td class="px-6 py-3 text-gray-800">{{ $item->anggota->nama ?? '-' }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full">
                                        {{ $item->shu->tahun ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right font-semibold text-green-700">
                                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="openEditModal({{ $item->id_shu_detail }}, {{ $item->jumlah }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <form action="{{ route('shu.destroy', $item->id_shu_detail) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Hapus data SHU ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors" title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300 block"></i>
                                    Belum ada data SHU
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($data->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $data->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

{{-- Modal Tambah --}}
<div id="createModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-green-600 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold"><i class="fas fa-plus-circle mr-2"></i>Tambah Data SHU</h3>
            <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
        </div>
        <form action="{{ route('shu.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Anggota <span class="text-red-500">*</span></label>
                <select name="id_anggota" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($anggota as $a)
                        <option value="{{ $a->id_anggota }}">{{ $a->no_anggota }} - {{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                <input type="number" name="tahun" value="{{ date('Y') }}" min="2000" max="2100" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah SHU (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" min="0" required placeholder="0"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('createModal')"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <div class="bg-yellow-500 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold"><i class="fas fa-edit mr-2"></i>Edit Jumlah SHU</h3>
            <button onclick="closeModal('editModal')" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah SHU (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="editJumlah" name="jumlah" min="0" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal('editModal')"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function openEditModal(id, jumlah) {
        document.getElementById('editForm').action = `/shu/${id}`;
        document.getElementById('editJumlah').value = jumlah;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Tutup modal saat klik backdrop
    ['createModal', 'editModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) closeModal(id);
        });
    });
</script>
@endsection
