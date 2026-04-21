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
@endsection

@section('konten')

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-green-700 to-green-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Berita & Pengumuman</h1>
            <p class="text-green-100 text-lg max-w-2xl mx-auto">
                Informasi terbaru seputar kegiatan, pengumuman, dan artikel dari Koperasi Surya Amaliah.
            </p>
        </div>
    </section>

    {{-- Filter Kategori --}}
    <section class="bg-white border-b border-gray-200 sticky top-[69px] z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto py-3">
                @php
                    $kategoriList = ['' => 'Semua', 'Berita' => 'Berita', 'Pengumuman' => 'Pengumuman', 'Artikel' => 'Artikel'];
                @endphp
                @foreach($kategoriList as $val => $label)
                    <a href="{{ route('berita.index', $val ? ['kategori' => $val] : []) }}"
                       class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-semibold transition
                              {{ $kategoriAktif === $val
                                  ? 'bg-koperasi-primary text-white shadow'
                                  : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Daftar Berita --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($berita->isEmpty())
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-newspaper text-6xl mb-4 block"></i>
                    <p class="text-xl">Belum ada konten untuk kategori ini.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($berita as $item)
                        <article class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 flex flex-col">
                            @if($item->gambar)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item->gambar) }}"
                                     alt="{{ $item->judul }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-5xl text-green-400"></i>
                                </div>
                            @endif

                            <div class="p-5 flex flex-col flex-1">
                                @php
                                    $colors = ['Berita'=>'bg-blue-100 text-blue-700','Pengumuman'=>'bg-red-100 text-red-700','Artikel'=>'bg-purple-100 text-purple-700'];
                                    $badgeClass = $colors[$item->kategori] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="{{ $badgeClass }} text-xs font-bold px-3 py-1 rounded-full inline-block w-fit mb-3">
                                    {{ $item->kategori }}
                                </span>

                                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 leading-snug">
                                    {{ $item->judul }}
                                </h3>

                                @if($item->ringkasan)
                                    <p class="text-gray-500 text-sm line-clamp-3 mb-4">{{ $item->ringkasan }}</p>
                                @endif

                                <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $item->tanggal->format('d M Y') }}
                                    </span>
                                    <a href="{{ route('berita.show', $item->slug) }}"
                                       class="text-koperasi-primary text-sm font-semibold hover:underline">
                                        Baca &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($berita->hasPages())
                    <div class="mt-10 flex justify-center">
                        {{ $berita->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>

@endsection
