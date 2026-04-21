@extends('layouts.users')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { padding: 0; border: none; }
    .ql-container.ql-snow.ql-disabled { border: none; }
    .ql-toolbar { display: none; }
</style>
@endsection

@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
    @include('components.navbar')

    <main class="content-area flex-1 overflow-y-auto p-6">

        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('manajemen-berita.index') }}"
               class="text-gray-500 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Berita</h2>
            </div>
            <div class="ml-auto flex gap-2">
                @if(auth()->user()->hasPermission('berita.edit'))
                    <a href="{{ route('manajemen-berita.edit', $berita) }}"
                       class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                @endif
                @if(auth()->user()->hasPermission('berita.delete'))
                    <form method="POST" action="{{ route('manajemen-berita.destroy', $berita) }}"
                          onsubmit="return confirm('Hapus berita ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($berita->gambar)
                <img src="{{ Storage::url($berita->gambar) }}" alt="{{ $berita->judul }}"
                     class="w-full h-64 object-cover">
            @endif

            <div class="p-8">
                <div class="flex items-center gap-3 mb-4">
                    @php $colors = ['Berita'=>'blue','Pengumuman'=>'red','Artikel'=>'purple']; $c = $colors[$berita->kategori] ?? 'gray'; @endphp
                    <span class="bg-{{ $c }}-100 text-{{ $c }}-700 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $berita->kategori }}
                    </span>
                    @if($berita->status === 'published')
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Published</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Draft</span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-800 mb-3">{{ $berita->judul }}</h1>

                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b border-gray-100">
                    <span><i class="fas fa-user mr-1"></i>{{ $berita->penulis?->name ?? 'Admin' }}</span>
                    <span><i class="fas fa-calendar mr-1"></i>{{ $berita->tanggal->format('d F Y') }}</span>
                    <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">/berita/{{ $berita->slug }}</span>
                </div>

                @if($berita->ringkasan)
                    <div class="bg-gray-50 border-l-4 border-simkop-green-dark rounded-r-lg p-4 mb-6 text-gray-600 italic">
                        {{ $berita->ringkasan }}
                    </div>
                @endif

                {{-- Render HTML dari Quill --}}
                <div class="ql-container ql-snow ql-disabled">
                    <div class="ql-editor text-gray-700 leading-relaxed">
                        {!! $berita->konten !!}
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
@endsection
