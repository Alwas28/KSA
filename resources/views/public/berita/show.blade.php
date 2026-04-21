@extends('layouts.home')

@section('css')
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'koperasi-primary':   '#059669',
                    'koperasi-secondary': '#1d4ed8',
                }
            }
        }
    }
</script>
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { padding: 0; font-size: 1rem; line-height: 1.75; }
    .ql-container.ql-snow.ql-disabled { border: none; }
    .ql-toolbar { display: none; }
    .ql-editor img { max-width: 100%; height: auto; border-radius: 0.75rem; margin: 1rem 0; }
</style>
@endsection

@section('konten')

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3 text-sm text-gray-500 flex items-center gap-2 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-koperasi-primary transition">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('berita.index') }}" class="hover:text-koperasi-primary transition">Berita</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 truncate max-w-xs">{{ $berita->judul }}</span>
        </div>
    </div>

    {{-- Artikel --}}
    <section class="py-10 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden">

                @if($berita->gambar)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($berita->gambar) }}"
                         alt="{{ $berita->judul }}"
                         class="w-full h-72 md:h-96 object-cover">
                @endif

                <div class="p-8 md:p-12">
                    @php
                        $colors = ['Berita'=>'bg-blue-100 text-blue-700','Pengumuman'=>'bg-red-100 text-red-700','Artikel'=>'bg-purple-100 text-purple-700'];
                        $badgeClass = $colors[$berita->kategori] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="{{ $badgeClass }} text-xs font-bold px-4 py-1.5 rounded-full inline-block mb-5">
                        {{ $berita->kategori }}
                    </span>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-5 leading-tight">
                        {{ $berita->judul }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 pb-6 mb-6 border-b border-gray-100">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-user-circle text-koperasi-primary"></i>
                            {{ $berita->penulis?->name ?? 'Admin KSA' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-calendar-alt text-koperasi-primary"></i>
                            {{ $berita->tanggal->format('d F Y') }}
                        </span>
                    </div>

                    @if($berita->ringkasan)
                        <div class="bg-green-50 border-l-4 border-koperasi-primary rounded-r-xl p-5 mb-8">
                            <p class="text-gray-600 italic leading-relaxed">{{ $berita->ringkasan }}</p>
                        </div>
                    @endif

                    {{-- Render HTML dari Quill --}}
                    <div class="ql-container ql-snow ql-disabled">
                        <div class="ql-editor text-gray-700">
                            {!! $berita->konten !!}
                        </div>
                    </div>
                </div>
            </article>

            <div class="mt-8">
                <a href="{{ route('berita.index') }}"
                   class="inline-flex items-center gap-2 text-koperasi-primary font-semibold hover:underline">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
                </a>
            </div>

            {{-- Berita Terkait --}}
            @if($related->isNotEmpty())
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-layer-group mr-2 text-koperasi-primary"></i>Konten Terkait
                    </h3>
                    <div class="grid sm:grid-cols-3 gap-6">
                        @foreach($related as $item)
                            <a href="{{ route('berita.show', $item->slug) }}"
                               class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col group">
                                @if($item->gambar)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->gambar) }}"
                                         alt="{{ $item->judul }}"
                                         class="w-full h-36 object-cover group-hover:opacity-90 transition">
                                @else
                                    <div class="w-full h-36 bg-green-50 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-4xl text-green-300"></i>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <p class="font-semibold text-gray-800 text-sm line-clamp-2 group-hover:text-koperasi-primary transition">
                                        {{ $item->judul }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $item->tanggal->format('d M Y') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
