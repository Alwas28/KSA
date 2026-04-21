@extends('layouts.users')

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
                <h2 class="text-2xl font-bold text-gray-800">Tambah Berita</h2>
                <p class="text-sm text-gray-500 mt-0.5">Buat konten berita, pengumuman, atau artikel baru</p>
            </div>
        </div>

        <form method="POST" action="{{ route('manajemen-berita.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl shadow-md p-6 space-y-5">
                <h3 class="font-semibold text-gray-700 border-b pb-3">Informasi Konten</h3>

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none @error('judul') border-red-400 @enderror"
                           placeholder="Masukkan judul berita..." required>
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori & Status --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none @error('kategori') border-red-400 @enderror">
                            @foreach(['Berita','Pengumuman','Artikel'] as $kat)
                                <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none @error('status') border-red-400 @enderror">
                            <option value="draft"     {{ old('status', 'draft') === 'draft'     ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Gambar URL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                    <input type="text" name="gambar_url" value="{{ old('gambar_url') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none @error('gambar_url') border-red-400 @enderror"
                           placeholder="https://... (opsional)">
                    <p class="text-xs text-gray-400 mt-1">Masukkan URL gambar thumbnail berita (opsional)</p>
                    @error('gambar_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Ringkasan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                    <textarea name="ringkasan" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none resize-none @error('ringkasan') border-red-400 @enderror"
                              placeholder="Ringkasan singkat berita (maks. 500 karakter)...">{{ old('ringkasan') }}</textarea>
                    @error('ringkasan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Konten --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten <span class="text-red-500">*</span></label>
                    <textarea name="konten" rows="14"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-green-500 focus:outline-none @error('konten') border-red-400 @enderror"
                              placeholder="Tulis isi berita di sini...">{{ old('konten') }}</textarea>
                    @error('konten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('manajemen-berita.index') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
                    Batal
                </a>
                <button type="submit"
                        class="bg-simkop-green-dark text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-green-800 transition">
                    <i class="fas fa-save mr-1"></i> Simpan Berita
                </button>
            </div>
        </form>

    </main>
</div>
@endsection
