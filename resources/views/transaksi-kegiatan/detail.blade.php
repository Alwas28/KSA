@extends('layouts.users')
@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
@include('components.navbar')
@include('components.toast')
<main class="content-area flex-1 overflow-y-auto p-6">
<div class="mb-6 flex justify-between items-center">
<div>
<h2 class="text-2xl font-bold text-gray-800">Detail Transaksi: {{ $kegiatan->nama_kegiatan }}</h2>
<p class="text-sm text-gray-600 mt-1">{{ $kegiatan->deskripsi }}</p>
</div>
<div class="flex gap-2">
<button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus mr-2"></i>Tambah Transaksi</button>
<a href="{{ route('transaksi-kegiatan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
</div>
</div>
<div class="grid grid-cols-3 gap-4 mb-6">
<div class="bg-green-100 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
<p class="text-green-600 text-sm font-medium">Total Pemasukan</p>
<h3 class="text-2xl font-bold text-green-700 mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
</div>
<div class="bg-red-100 rounded-xl shadow-lg p-6 border-l-4 border-red-500">
<p class="text-red-600 text-sm font-medium">Total Pengeluaran</p>
<h3 class="text-2xl font-bold text-red-700 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
</div>
<div class="bg-blue-100 rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
<p class="text-blue-600 text-sm font-medium">Saldo</p>
<h3 class="text-2xl font-bold text-blue-700 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
</div>
</div>
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
<div class="px-6 py-4 bg-green-600 text-white"><h4 class="text-lg font-bold"><i class="fas fa-list mr-2"></i>Daftar Transaksi</h4></div>
<div class="overflow-x-auto p-6">
<table id="transaksiTable" class="w-full">
<thead class="bg-green-600 text-white">
<tr>
<th class="px-6 py-4 text-left">No</th>
<th class="px-6 py-4 text-left">Tanggal</th>
<th class="px-6 py-4 text-center">Jenis</th>
<th class="px-6 py-4 text-right">Nominal</th>
<th class="px-6 py-4 text-left">Keterangan</th>
<th class="px-6 py-4 text-center">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200">
@forelse($transaksi as $index => $item)
<tr class="hover:bg-gray-50">
<td class="px-6 py-4">{{ $index + 1 }}</td>
<td class="px-6 py-4">{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
<td class="px-6 py-4 text-center">
@if($item->jenis_transaksi == 'pemasukan')
<span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800"><i class="fas fa-arrow-up mr-1"></i>Pemasukan</span>
@else
<span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800"><i class="fas fa-arrow-down mr-1"></i>Pengeluaran</span>
@endif
</td>
<td class="px-6 py-4 text-right font-semibold {{ $item->jenis_transaksi == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
<td class="px-6 py-4">{{ $item->keterangan }}</td>
<td class="px-6 py-4 text-center">
<form action="{{ route('transaksi-kegiatan.destroy', $item->id_transaksi) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
@csrf
@method('DELETE')
<button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs"><i class="fas fa-trash"></i></button>
</form>
</td>
</tr>
@empty
<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-2"></i><p>Belum ada transaksi</p></td></tr>
@endforelse
</tbody>
</table>
</div>
</div>
</main>
</div>
<div id="addModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
<div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
<div class="flex justify-between items-center mb-6">
<h3 class="text-2xl font-bold text-gray-800"><i class="fas fa-plus-circle text-green-600 mr-3"></i>Tambah Transaksi</h3>
<button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-2xl"></i></button>
</div>
<form action="{{ route('transaksi-kegiatan.store') }}" method="POST" class="space-y-4">
@csrf
<input type="hidden" name="id_kegiatan" value="{{ $kegiatan->id_kegiatan }}">
<input type="hidden" name="from_detail" value="1">
<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
<input type="date" name="tanggal_transaksi" id="tanggal_transaksi" required class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Transaksi <span class="text-red-500">*</span></label>
<select name="jenis_transaksi" required class="w-full px-4 py-2 border rounded-lg">
<option value="">Pilih Jenis</option>
<option value="pemasukan">Pemasukan</option>
<option value="pengeluaran">Pengeluaran</option>
</select>
</div>
<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
<input type="number" name="nominal" required min="0" step="0.01" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan <span class="text-red-500">*</span></label>
<textarea name="keterangan" required rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
</div>
<div class="flex gap-3 pt-4">
<button type="button" onclick="closeAddModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg"><i class="fas fa-times mr-2"></i>Batal</button>
<button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"><i class="fas fa-save mr-2"></i>Simpan</button>
</div>
</form>
</div>
</div>
@endsection
@section('css')<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){$('#transaksiTable').DataTable({"language":{"url":"//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"},"pageLength":10,"order":[[1,'desc']]});document.getElementById('tanggal_transaksi').value=new Date().toISOString().split('T')[0];});
function openAddModal(){document.getElementById('addModal').classList.remove('hidden');}
function closeAddModal(){document.getElementById('addModal').classList.add('hidden');}
window.onclick=function(event){if(event.target==document.getElementById('addModal')){closeAddModal();}}
</script>
@endsection
