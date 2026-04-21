@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Data Pinjaman</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola data pinjaman anggota koperasi</p>
                    </div>
                    @if(auth()->user()->hasPermission('pinjaman.create'))
                        <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium">
                            <i class="fas fa-plus-circle"></i>
                            <span>Tambah Pinjaman</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-100 rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-medium">Diajukan</p>
                            <h3 class="text-2xl font-bold text-blue-700 mt-1">{{ $pinjaman->where('status', 'diajukan')->count() }}</h3>
                        </div>
                        <div class="bg-blue-200 rounded-full p-3">
                            <i class="fas fa-clock text-blue-700 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-green-100 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-sm font-medium">Disetujui</p>
                            <h3 class="text-2xl font-bold text-green-700 mt-1">{{ $pinjaman->where('status', 'disetujui')->count() }}</h3>
                        </div>
                        <div class="bg-green-200 rounded-full p-3">
                            <i class="fas fa-check-circle text-green-700 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-cyan-100 rounded-xl shadow-lg p-6 border-l-4 border-cyan-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-cyan-600 text-sm font-medium">Dicairkan</p>
                            <h3 class="text-2xl font-bold text-cyan-700 mt-1">{{ $pinjaman->where('status', 'dicairkan')->count() }}</h3>
                        </div>
                        <div class="bg-cyan-200 rounded-full p-3">
                            <i class="fas fa-money-bill-wave text-cyan-700 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-red-100 rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-600 text-sm font-medium">Ditolak</p>
                            <h3 class="text-2xl font-bold text-red-700 mt-1">{{ $pinjaman->where('status', 'ditolak')->count() }}</h3>
                        </div>
                        <div class="bg-red-200 rounded-full p-3">
                            <i class="fas fa-times-circle text-red-700 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-100 rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-600 text-sm font-medium">Lunas</p>
                            <h3 class="text-2xl font-bold text-purple-700 mt-1">{{ $pinjaman->where('status', 'lunas')->count() }}</h3>
                        </div>
                        <div class="bg-purple-200 rounded-full p-3">
                            <i class="fas fa-check-double text-purple-700 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="text-lg font-bold">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        Daftar Pinjaman
                    </h4>
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="pinjamanTable" class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Anggota</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tgl Pengajuan</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Pokok Pinjaman</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Lama</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wider">Angsuran/Bulan</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pinjaman as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($item->anggota->nama, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-semibold text-gray-900">{{ $item->anggota->nama }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->anggota->no_anggota }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $item->tanggal_pengajuan->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-700">
                                        {{ $item->lama_angsuran }} bulan
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-semibold text-green-600">
                                        Rp {{ number_format($item->angsuran_per_bulan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->status === 'diajukan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Diajukan
                                            </span>
                                        @elseif($item->status === 'disetujui')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Disetujui
                                            </span>
                                        @elseif($item->status === 'dicairkan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-800">
                                                <i class="fas fa-money-bill-wave mr-1"></i>
                                                Dicairkan
                                            </span>
                                        @elseif($item->status === 'ditolak')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Ditolak
                                            </span>
                                        @elseif($item->status === 'lunas')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                <i class="fas fa-check-double mr-1"></i>
                                                Lunas
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            @if($item->status === 'diajukan')
                                                @if(auth()->user()->hasPermission('pinjaman.update'))
                                                    <button type="button" onclick="openApproveModal({{ $item->id_pinjaman }})" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <form action="{{ route('pinjaman.reject', $item->id_pinjaman) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Tolak" onclick="return confirm('Apakah Anda yakin ingin menolak pinjaman ini?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            @if(auth()->user()->hasPermission('pinjaman.update') && !in_array($item->status, ['dicairkan', 'lunas']))
                                                <button onclick="openEditModal({{ $item->id_pinjaman }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            @if(auth()->user()->hasPermission('pinjaman.delete') && $item->status !== 'lunas')
                                                <form action="{{ route('pinjaman.destroy', $item->id_pinjaman) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pinjaman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded transition-colors duration-200 text-xs" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data pinjaman</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Pinjaman -->
    <div id="addModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="text-xl font-bold">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Pinjaman
                </h3>
                <button onclick="closeAddModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('pinjaman.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Anggota <span class="text-red-500">*</span></label>
                        <select name="id_anggota" id="addIdAnggota" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent select2-add">
                            <option value="">Pilih Anggota</option>
                            @foreach($anggota as $member)
                                <option value="{{ $member->id_anggota }}">{{ $member->no_anggota }} - {{ $member->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pengajuan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pokok Pinjaman (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="pokok_pinjaman" id="addPokokPinjaman" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" onkeyup="hitungTotalAdd()">
                        <p class="text-xs text-gray-500 mt-1">Masukkan jumlah pinjaman yang diajukan</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lama Angsuran (Bulan) <span class="text-red-500">*</span></label>
                        <input type="number" name="lama_angsuran" id="addLamaAngsuran" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" onkeyup="hitungTotalAdd()">
                        <p class="text-xs text-gray-500 mt-1">Jangka waktu pelunasan</p>
                    </div>

                    <!-- Kalkulasi Preview -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calculator mr-2 text-green-600"></i>
                            Rincian Perhitungan
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pokok Pinjaman:</span>
                                <span class="font-semibold" id="addPreviewPokok">Rp 0</span>
                            </div>
                            <div class="border-t border-gray-300 pt-2 flex justify-between">
                                <span class="font-semibold text-gray-700">Angsuran per Bulan (<span id="addPreviewLama">0</span> bulan):</span>
                                <span class="font-bold text-green-600" id="addPreviewAngsuran">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                            <option value="diajukan" selected>Diajukan</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="dicairkan">Dicairkan</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pinjaman -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-yellow-500 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="text-xl font-bold">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Pinjaman
                </h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Anggota <span class="text-red-500">*</span></label>
                        <select name="id_anggota" id="editIdAnggota" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent select2-edit">
                            <option value="">Pilih Anggota</option>
                            @foreach($anggota as $member)
                                <option value="{{ $member->id_anggota }}">{{ $member->no_anggota }} - {{ $member->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pengajuan" id="editTanggalPengajuan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pokok Pinjaman (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="pokok_pinjaman" id="editPokokPinjaman" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" onkeyup="hitungTotalEdit()">
                        <p class="text-xs text-gray-500 mt-1">Masukkan jumlah pinjaman yang diajukan</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Lama Angsuran (Bulan) <span class="text-red-500">*</span></label>
                        <input type="number" name="lama_angsuran" id="editLamaAngsuran" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" onkeyup="hitungTotalEdit()">
                        <p class="text-xs text-gray-500 mt-1">Jangka waktu pelunasan</p>
                    </div>

                    <!-- Kalkulasi Preview -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calculator mr-2 text-yellow-600"></i>
                            Rincian Perhitungan
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pokok Pinjaman:</span>
                                <span class="font-semibold" id="editPreviewPokok">Rp 0</span>
                            </div>
                            <div class="border-t border-gray-300 pt-2 flex justify-between">
                                <span class="font-semibold text-gray-700">Angsuran per Bulan (<span id="editPreviewLama">0</span> bulan):</span>
                                <span class="font-bold text-green-600" id="editPreviewAngsuran">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="editStatus" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <option value="diajukan">Diajukan</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="dicairkan">Dicairkan</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Approve Pinjaman -->
    <div id="approveModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="text-xl font-bold">
                    <i class="fas fa-check-circle mr-2"></i>
                    Setujui Pinjaman
                </h3>
                <button onclick="closeApproveModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="approveForm" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 text-xl mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-700 mb-1">Informasi Persetujuan</p>
                            <p class="text-sm text-blue-600">Anda dapat menyesuaikan lama angsuran sesuai kebijakan koperasi sebelum menyetujui pinjaman ini.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Info Anggota -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-user mr-2 text-green-600"></i>
                            Data Pemohon
                        </h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Nama Anggota:</span>
                                <p class="font-semibold text-gray-800" id="approveNamaAnggota">-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">No. Anggota:</span>
                                <p class="font-semibold text-gray-800" id="approveNoAnggota">-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Tanggal Pengajuan:</span>
                                <p class="font-semibold text-gray-800" id="approveTanggalPengajuan">-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Pokok Pinjaman:</span>
                                <p class="font-semibold text-green-600 text-lg" id="approvePokokPinjaman">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pengajuan Lama Angsuran -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <strong>Lama angsuran yang diajukan:</strong> <span id="approveLamaAngsuranAwal" class="font-bold">0</span> bulan
                        </p>
                        <p class="text-xs text-yellow-600">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Angsuran per bulan: <span id="approveAngsuranPerBulanAwal" class="font-semibold">Rp 0</span>
                        </p>
                    </div>

                    <!-- Input Lama Angsuran yang Disetujui -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Lama Angsuran yang Disetujui (Bulan) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="lama_angsuran" id="approveLamaAngsuran" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            onkeyup="hitungAngsuranApprove()">
                        <p class="text-xs text-gray-500 mt-1">Sesuaikan jangka waktu pelunasan jika diperlukan</p>
                    </div>

                    <!-- Preview Perhitungan Setelah Persetujuan -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calculator mr-2 text-green-600"></i>
                            Rincian Setelah Persetujuan
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pokok Pinjaman:</span>
                                <span class="font-semibold text-gray-800" id="approvePreviewPokok">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Lama Angsuran:</span>
                                <span class="font-semibold text-gray-800"><span id="approvePreviewLama">0</span> bulan</span>
                            </div>
                            <div class="border-t border-green-300 pt-2 flex justify-between">
                                <span class="font-semibold text-gray-700">Angsuran per Bulan:</span>
                                <span class="font-bold text-green-600 text-lg" id="approvePreviewAngsuran">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Input untuk Pokok Pinjaman -->
                    <input type="hidden" id="approvePokokPinjamanValue" value="0">
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeApproveModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium">
                        <i class="fas fa-check-circle mr-2"></i>
                        Setujui Pinjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
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
            right: 10px;
        }
        .select2-dropdown {
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
        }
    </style>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#pinjamanTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "order": [[2, 'desc']]
            });

            // Initialize Select2 for Add Modal
            $('.select2-add').select2({
                dropdownParent: $('#addModal'),
                placeholder: 'Cari anggota...',
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for Edit Modal
            $('.select2-edit').select2({
                dropdownParent: $('#editModal'),
                placeholder: 'Cari anggota...',
                allowClear: true,
                width: '100%'
            });
        });

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
            // Reinitialize Select2 when modal opens
            setTimeout(() => {
                $('.select2-add').select2({
                    dropdownParent: $('#addModal'),
                    placeholder: 'Cari anggota...',
                    allowClear: true,
                    width: '100%'
                });
            }, 100);
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
            // Reset form
            document.getElementById('addPokokPinjaman').value = '';
            document.getElementById('addLamaAngsuran').value = '';
            hitungTotalAdd();
        }

        function openEditModal(id) {
            fetch(`/pinjaman/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editForm').action = `/pinjaman/${id}`;
                    document.getElementById('editIdAnggota').value = data.id_anggota;
                    document.getElementById('editTanggalPengajuan').value = data.tanggal_pengajuan.split(' ')[0];
                    document.getElementById('editPokokPinjaman').value = data.pokok_pinjaman;
                    document.getElementById('editLamaAngsuran').value = data.lama_angsuran;
                    document.getElementById('editStatus').value = data.status;

                    // Trigger calculation
                    hitungTotalEdit();

                    // Reinitialize Select2 for edit modal
                    $('.select2-edit').val(data.id_anggota).trigger('change');

                    document.getElementById('editModal').classList.remove('hidden');
                    document.getElementById('editModal').classList.add('flex');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        // Fungsi hitung total untuk Add Modal
        function hitungTotalAdd() {
            const pokok = parseFloat(document.getElementById('addPokokPinjaman').value) || 0;
            const lama = parseInt(document.getElementById('addLamaAngsuran').value) || 0;

            const angsuranPerBulan = lama > 0 ? pokok / lama : 0;

            document.getElementById('addPreviewPokok').textContent = 'Rp ' + pokok.toLocaleString('id-ID');
            document.getElementById('addPreviewLama').textContent = lama;
            document.getElementById('addPreviewAngsuran').textContent = 'Rp ' + angsuranPerBulan.toLocaleString('id-ID');
        }

        // Fungsi hitung total untuk Edit Modal
        function hitungTotalEdit() {
            const pokok = parseFloat(document.getElementById('editPokokPinjaman').value) || 0;
            const lama = parseInt(document.getElementById('editLamaAngsuran').value) || 0;

            const angsuranPerBulan = lama > 0 ? pokok / lama : 0;

            document.getElementById('editPreviewPokok').textContent = 'Rp ' + pokok.toLocaleString('id-ID');
            document.getElementById('editPreviewLama').textContent = lama;
            document.getElementById('editPreviewAngsuran').textContent = 'Rp ' + angsuranPerBulan.toLocaleString('id-ID');
        }

        // Fungsi untuk modal Approve
        function openApproveModal(id) {
            fetch(`/pinjaman/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data pinjaman:', data); // Debug log

                    // Set form action
                    document.getElementById('approveForm').action = `/pinjaman/${id}/approve`;

                    // Set data pemohon
                    document.getElementById('approveNamaAnggota').textContent = data.anggota.nama;
                    document.getElementById('approveNoAnggota').textContent = data.anggota.no_anggota;
                    document.getElementById('approveTanggalPengajuan').textContent = new Date(data.tanggal_pengajuan).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    document.getElementById('approvePokokPinjaman').textContent = 'Rp ' + parseFloat(data.pokok_pinjaman).toLocaleString('id-ID');

                    // Set hidden value pokok pinjaman
                    document.getElementById('approvePokokPinjamanValue').value = data.pokok_pinjaman;

                    // Set lama angsuran yang diajukan
                    document.getElementById('approveLamaAngsuranAwal').textContent = data.lama_angsuran;
                    const angsuranAwal = data.pokok_pinjaman / data.lama_angsuran;
                    document.getElementById('approveAngsuranPerBulanAwal').textContent = 'Rp ' + angsuranAwal.toLocaleString('id-ID');

                    // Set default lama angsuran yang disetujui = lama angsuran pengajuan
                    document.getElementById('approveLamaAngsuran').value = data.lama_angsuran;

                    // Trigger calculation
                    hitungAngsuranApprove();

                    // Show modal
                    document.getElementById('approveModal').classList.remove('hidden');
                    document.getElementById('approveModal').classList.add('flex');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data pinjaman. Silakan coba lagi.');
                });
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
        }

        // Fungsi hitung angsuran untuk Approve Modal
        function hitungAngsuranApprove() {
            const pokok = parseFloat(document.getElementById('approvePokokPinjamanValue').value) || 0;
            const lama = parseInt(document.getElementById('approveLamaAngsuran').value) || 0;

            const angsuranPerBulan = lama > 0 ? pokok / lama : 0;

            document.getElementById('approvePreviewPokok').textContent = 'Rp ' + pokok.toLocaleString('id-ID');
            document.getElementById('approvePreviewLama').textContent = lama;
            document.getElementById('approvePreviewAngsuran').textContent = 'Rp ' + angsuranPerBulan.toLocaleString('id-ID');
        }


        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) {
                closeAddModal();
            }
            if (event.target == document.getElementById('editModal')) {
                closeEditModal();
            }
            if (event.target == document.getElementById('approveModal')) {
                closeApproveModal();
            }
        }
    </script>
@endsection
