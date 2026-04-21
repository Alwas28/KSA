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

        {{-- Filter & Search --}}
        <form method="GET" action="{{ route('shu.index') }}"
              class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-end">
            {{-- Pertahankan sort saat filter --}}
            <input type="hidden" name="sort" value="{{ $sortCol }}">
            <input type="hidden" name="dir"  value="{{ $sortDir }}">

            {{-- Search --}}
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Cari Anggota</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Nama atau No Anggota..."
                           class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>
            </div>

            {{-- Filter Tahun --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tahun</label>
                <select name="tahun"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahunFilter == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                <i class="fas fa-search mr-1"></i> Cari
            </button>
            <a href="{{ route('shu.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
                Reset
            </a>
        </form>

        {{-- Tabel Data SHU --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-green-600 text-white">
                <h4 class="font-bold text-lg"><i class="fas fa-chart-pie mr-2"></i>Data SHU Anggota</h4>
            </div>

            @php
                // Helper: buat URL sort per kolom
                function shuSortUrl(string $col, string $currentCol, string $currentDir, array $query): string {
                    $dir = ($currentCol === $col && $currentDir === 'asc') ? 'desc' : 'asc';
                    return route('shu.index', array_merge($query, ['sort' => $col, 'dir' => $dir]));
                }
                $qBase = array_filter([
                    'tahun'  => $tahunFilter,
                    'search' => $search,
                ]);
            @endphp
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>

                            {{-- No Anggota --}}
                            <th class="px-6 py-3 text-left">
                                <a href="{{ shuSortUrl('no_anggota', $sortCol, $sortDir, $qBase) }}"
                                   class="inline-flex items-center gap-1 hover:text-green-700 transition">
                                    No Anggota
                                    @if($sortCol === 'no_anggota')
                                        <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} text-green-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>

                            {{-- Nama Anggota --}}
                            <th class="px-6 py-3 text-left">
                                <a href="{{ shuSortUrl('nama', $sortCol, $sortDir, $qBase) }}"
                                   class="inline-flex items-center gap-1 hover:text-green-700 transition">
                                    Nama Anggota
                                    @if($sortCol === 'nama')
                                        <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} text-green-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>

                            {{-- Tahun --}}
                            <th class="px-6 py-3 text-center">
                                <a href="{{ shuSortUrl('tahun', $sortCol, $sortDir, $qBase) }}"
                                   class="inline-flex items-center justify-center gap-1 hover:text-green-700 transition">
                                    Tahun
                                    @if($sortCol === 'tahun')
                                        <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} text-green-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>

                            {{-- Jumlah SHU --}}
                            <th class="px-6 py-3 text-right">
                                <a href="{{ shuSortUrl('jumlah', $sortCol, $sortDir, $qBase) }}"
                                   class="inline-flex items-center justify-end gap-1 hover:text-green-700 transition">
                                    Jumlah SHU
                                    @if($sortCol === 'jumlah')
                                        <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} text-green-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-300"></i>
                                    @endif
                                </a>
                            </th>

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
<div id="createModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50" style="display:none">
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
<div id="editModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50" style="display:none">
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
        document.getElementById('createModal').style.display = 'flex';
    }

    function openEditModal(id, jumlah) {
        document.getElementById('editForm').action = `/shu/${id}`;
        document.getElementById('editJumlah').value = jumlah;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    // Tutup modal saat klik backdrop
    ['createModal', 'editModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) closeModal(id);
        });
    });
</script>
@endsection
