@extends('layouts.users')
@use('Illuminate\Support\Facades\Storage')

@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
    @include('components.navbar')
    @include('components.toast')

    <main class="content-area flex-1 overflow-y-auto p-6">

        <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Berita</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola berita, pengumuman, dan artikel website</p>
            </div>
            @if(auth()->user()->hasPermission('berita.create'))
                <a href="{{ route('manajemen-berita.create') }}"
                   class="inline-flex items-center gap-2 bg-simkop-green-dark text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-800 transition">
                    <i class="fas fa-plus"></i> Tambah Berita
                </a>
            @endif
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('manajemen-berita.index') }}"
              class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                <select name="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach(['Berita','Pengumuman','Artikel'] as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <button type="submit"
                    class="bg-simkop-green-dark text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-800 transition">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('manajemen-berita.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
                Reset
            </a>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-simkop-green-dark text-white flex items-center gap-2">
                <i class="fas fa-newspaper"></i>
                <h4 class="font-bold text-lg">Daftar Berita</h4>
                <span class="ml-auto bg-white text-simkop-green-dark text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ $berita->total() }} data
                </span>
            </div>

            @if($berita->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-newspaper text-5xl mb-4 text-gray-300 block"></i>
                    <p class="text-lg">Belum ada berita</p>
                    <p class="text-sm text-gray-400 mt-1">Klik tombol "Tambah Berita" untuk membuat konten baru</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Judul</th>
                                <th class="px-4 py-3 text-center">Kategori</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-left">Penulis</th>
                                <th class="px-4 py-3 text-center">Tanggal</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($berita as $i => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-500">{{ $berita->firstItem() + $i }}</td>
                                    <td class="px-4 py-3 max-w-xs">
                                        <p class="font-semibold text-gray-800 truncate">{{ $item->judul }}</p>
                                        @if($item->ringkasan)
                                            <p class="text-xs text-gray-400 truncate mt-0.5">{{ $item->ringkasan }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $colors = ['Berita'=>'blue','Pengumuman'=>'red','Artikel'=>'purple'];
                                            $c = $colors[$item->kategori] ?? 'gray';
                                        @endphp
                                        <span class="bg-{{ $c }}-100 text-{{ $c }}-700 text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $item->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($item->status === 'published')
                                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Published</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">
                                        {{ $item->penulis?->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-xs text-gray-500">
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('manajemen-berita.show', $item) }}"
                                               class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-600 transition">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasPermission('berita.edit'))
                                                <a href="{{ route('manajemen-berita.edit', $item) }}"
                                                   class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-yellow-600 transition">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('berita.delete'))
                                                <form method="POST" action="{{ route('manajemen-berita.destroy', $item) }}"
                                                      onsubmit="return confirm('Hapus berita ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-600 transition">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($berita->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $berita->links() }}
                    </div>
                @endif
            @endif
        </div>

    </main>
</div>
@endsection
