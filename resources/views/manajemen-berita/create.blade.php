@extends('layouts.users')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<style>
    #quill-editor { min-height: 320px; font-size: 15px; }
    .ql-toolbar.ql-snow { border-radius: 0.5rem 0.5rem 0 0; border-color: #d1d5db; }
    .ql-container.ql-snow { border-radius: 0 0 0.5rem 0.5rem; border-color: #d1d5db; }
    #gambar-preview { display:none; max-height:200px; border-radius:0.5rem; margin-top:0.5rem; object-fit:cover; }
</style>
@endsection

@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
    @include('components.navbar')

    <main class="content-area flex-1 overflow-y-auto p-6">

        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('manajemen-berita.index') }}" class="text-gray-500 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Berita</h2>
                <p class="text-sm text-gray-500 mt-0.5">Buat konten berita, pengumuman, atau artikel baru</p>
            </div>
        </div>

        <form method="POST" action="{{ route('manajemen-berita.store') }}"
              enctype="multipart/form-data" class="space-y-6" id="berita-form">
            @csrf

            <div class="bg-white rounded-xl shadow-md p-6 space-y-5">
                <h3 class="font-semibold text-gray-700 border-b pb-3">Informasi Konten</h3>

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none @error('judul') border-red-400 @enderror"
                           placeholder="Masukkan judul berita..." required>
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 text-sm flex-shrink-0">/berita/</span>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-green-500 focus:outline-none @error('slug') border-red-400 @enderror"
                               placeholder="auto-generate-dari-judul">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-magic mr-1"></i>Otomatis dibuat dari judul. Bisa diedit manual.
                    </p>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori, Status, Tanggal --}}
                <div class="grid md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                            @foreach(['Berita','Pengumuman','Artikel'] as $kat)
                                <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal"
                               value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="draft"     {{ old('status', 'draft') === 'draft'     ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Gambar Thumbnail --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Thumbnail</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-green-400 transition cursor-pointer"
                         onclick="document.getElementById('gambar-input').click()">
                        <div id="upload-placeholder" class="text-center py-4">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 block mb-2"></i>
                            <p class="text-sm text-gray-500">Klik untuk upload gambar</p>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP — maks. 2MB</p>
                        </div>
                        <img id="gambar-preview" src="#" alt="Preview">
                    </div>
                    <input type="file" name="gambar" id="gambar-input" accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="hidden" onchange="previewGambar(this)">
                    @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Ringkasan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                    <textarea name="ringkasan" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none resize-none @error('ringkasan') border-red-400 @enderror"
                              placeholder="Ringkasan singkat (maks. 500 karakter)...">{{ old('ringkasan') }}</textarea>
                    @error('ringkasan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Konten (Quill Editor) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Konten <span class="text-red-500">*</span>
                    </label>
                    <div id="quill-editor">{!! old('konten') !!}</div>
                    <input type="hidden" name="konten" id="konten-input">
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    // ── Quill Editor ──────────────────────────────────────────────
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis isi berita di sini...',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ align: [] }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Custom image handler – upload ke server
    quill.getModule('toolbar').addHandler('image', function () {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/png,image/jpeg,image/webp,image/gif');
        input.click();
        input.onchange = async () => {
            const file = input.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');
            try {
                const res  = await fetch('{{ route("berita.upload-image") }}', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.url) {
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', data.url);
                    quill.setSelection(range.index + 1);
                }
            } catch (e) {
                alert('Gagal upload gambar. Coba lagi.');
            }
        };
    });

    // Sync Quill HTML ke hidden input sebelum submit
    document.getElementById('berita-form').addEventListener('submit', function () {
        document.getElementById('konten-input').value = quill.root.innerHTML;
    });

    // ── Auto-slug dari judul ──────────────────────────────────────
    let slugEdited = false;
    document.getElementById('slug').addEventListener('input', () => slugEdited = true);

    document.getElementById('judul').addEventListener('input', function () {
        if (slugEdited) return;
        document.getElementById('slug').value = toSlug(this.value);
    });

    function toSlug(str) {
        return str.toLowerCase()
            .replace(/[àáâãäå]/g, 'a').replace(/[èéêë]/g, 'e')
            .replace(/[ìíîï]/g, 'i').replace(/[òóôõö]/g, 'o')
            .replace(/[ùúûü]/g, 'u').replace(/[ñ]/g, 'n')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim().replace(/\s+/g, '-').replace(/-+/g, '-');
    }

    // ── Preview gambar ────────────────────────────────────────────
    function previewGambar(input) {
        const preview     = document.getElementById('gambar-preview');
        const placeholder = document.getElementById('upload-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
