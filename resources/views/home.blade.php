@extends('layouts.home')
@section('css')
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'koperasi-primary': '#059669', // Hijau Emerald
                        'koperasi-secondary': '#1d4ed8', // Biru Tua
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(100%)' },
                            '100%': { transform: 'translateX(0)' },
                        }
                    },
                    animation: {
                        fadeIn: 'fadeIn 1.5s ease-out',
                        slideIn: 'slideIn 1s ease-out',
                    }
                }
            }
        }
    </script>
    
@endsection

@section('konten')
    <!-- HEADER BARU (HERO SECTION) DUA KOLOM -->
    <section id="hero-header" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-20 md:py-24">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            
            <!-- Kolom Kiri: Teks dan Tombol CTA -->
            <div class="lg:order-1 order-2">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    <!-- Koperasi Surya Amaliah <span class="text-koperasi-primary">Terpercaya</span> -->
                    <span class="block mt-2 text-koperasi-primary">Koperasi Surya Amaliah</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-lg">
                    Koperasi Berbasis Syariah yang Menyediakan Solusi Keuangan dan Pemberdayaan Ekonomi untuk Anggota dan Masyarakat Universitas Muhammadiyah Kendari.
                </p>

                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="#" class="inline-flex justify-center items-center bg-koperasi-primary text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:bg-green-700 transition duration-300 text-lg">
                        Daftar Anggota Baru <i class="fas fa-user-plus ml-2"></i>
                    </a>
                    <a href="#produk" class="inline-flex justify-center items-center text-koperasi-primary border-2 border-koperasi-primary font-semibold px-8 py-4 rounded-xl hover:bg-koperasi-primary hover:text-white transition duration-300 text-lg">
                        Lihat Produk & Layanan <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Gambar Promosi -->
            <div class="lg:order-2 order-1 flex justify-center p-4">
                <div class="bg-white p-1 shadow-2xl rounded-3xl max-w-full lg:max-w-none">
                    <img class="w-full h-auto object-cover rounded-2xl" 
                         src="https://cdni.iconscout.com/illustration/premium/thumb/green-energy-4991727-4177939.png" 
                         alt="Kantor Koperasi yang Modern dan Terpercaya"
                         style="max-height: 450px;">
                </div>
            </div>

        </div>
    </section>
    <!-- AKHIR HERO HEADER -->

    <main>
        
        <!-- Konten Tetap Sama Setelah Hero Header -->
        
        <section id="profil" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-koperasi-secondary">Tentang Kami</h2>
                <div class="grid md:grid-cols-2 gap-10">
                    <div>
                        <h3 class="text-2xl font-semibold text-koperasi-primary mb-3">Visi & Misi</h3>
                        <p class="text-gray-600 mb-6">**Visi:** Menjadi Koperasi modern dan terpercaya yang menyejahterakan anggota.</p>
                        <p class="text-gray-600">**Misi:** Memberikan layanan keuangan yang transparan, profesional, dan berkelanjutan.</p>
                        
                        <h3 class="text-2xl font-semibold text-koperasi-primary mt-8 mb-3">Sejarah Singkat</h3>
                        <p class="text-gray-600">Didirikan pada tahun 1995, Koperasi Sejahtera Bersama telah melayani lebih dari 5.000 anggota, berfokus pada pemberdayaan ekonomi lokal.</p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-koperasi-primary mb-3">Struktur Organisasi</h3>
                        <div class="bg-gray-100 p-6 rounded-lg border border-gray-200">
                             <i class="fas fa-sitemap text-3xl text-koperasi-secondary mb-2"></i>
                            <p class="text-sm text-gray-600">Klik di sini untuk melihat bagan struktur organisasi lengkap (Ketua, Pengawas, Pengelola, Anggota).</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="produk" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-koperasi-secondary">Produk & Layanan Kami</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-koperasi-primary">
                        <i class="fas fa-wallet text-3xl text-koperasi-primary mb-4"></i>
                        <h3 class="text-xl font-semibold mb-3">Simpanan Anggota</h3>
                        <p class="text-gray-600">Simpanan pokok, wajib, dan sukarela dengan bagi hasil yang kompetitif.</p>
                        <a href="#" class="text-koperasi-secondary font-medium mt-3 inline-block hover:underline">Detail Simpanan &rarr;</a>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-koperasi-secondary">
                        <i class="fas fa-hand-holding-usd text-3xl text-koperasi-secondary mb-4"></i>
                        <h3 class="text-xl font-semibold mb-3">Pinjaman Modal Usaha</h3>
                        <p class="text-gray-600">Bantuan pinjaman untuk pengembangan usaha kecil dan menengah anggota.</p>
                        <a href="#" class="text-koperasi-secondary font-medium mt-3 inline-block hover:underline">Ajukan Pinjaman &rarr;</a>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-yellow-500">
                        <i class="fas fa-store text-3xl text-yellow-500 mb-4"></i>
                        <h3 class="text-xl font-semibold mb-3">Toko Koperasi (Unit Usaha)</h3>
                        <p class="text-gray-600">Menyediakan kebutuhan sehari-hari dengan harga khusus bagi anggota.</p>
                        <a href="#" class="text-koperasi-secondary font-medium mt-3 inline-block hover:underline">Kunjungi Toko &rarr;</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="berita" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-12">
                    <h2 class="text-4xl font-bold text-koperasi-secondary">Berita Terbaru</h2>
                    <a href="{{ route('berita.index') }}"
                       class="text-koperasi-secondary hover:text-koperasi-primary font-semibold transition">
                        Lihat Semua <i class="fas fa-angle-right ml-1"></i>
                    </a>
                </div>

                @if(isset($berita) && $berita->isNotEmpty())
                    <div class="grid md:grid-cols-3 gap-8">
                        @foreach($berita as $item)
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 flex flex-col">
                                @if($item->gambar)
                                    <img class="w-full h-40 object-cover"
                                         src="{{ \Illuminate\Support\Facades\Storage::url($item->gambar) }}"
                                         alt="{{ $item->judul }}">
                                @else
                                    @php
                                        $gradients = ['Pengumuman'=>'bg-red-100','Artikel'=>'bg-purple-100','Berita'=>'bg-blue-100'];
                                        $icons = ['Pengumuman'=>'fa-bullhorn text-red-400','Artikel'=>'fa-scroll text-purple-400','Berita'=>'fa-newspaper text-blue-400'];
                                        $g = $gradients[$item->kategori] ?? 'bg-green-100';
                                        $ic = $icons[$item->kategori] ?? 'fa-newspaper text-green-400';
                                    @endphp
                                    <div class="w-full h-40 {{ $g }} flex items-center justify-center">
                                        <i class="fas {{ $ic }} text-4xl"></i>
                                    </div>
                                @endif

                                @php
                                    $badgeColors = ['Pengumuman'=>'bg-red-100 text-red-800','Artikel'=>'bg-purple-100 text-purple-800','Berita'=>'bg-blue-100 text-blue-800'];
                                    $badge = $badgeColors[$item->kategori] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <div class="p-6 flex flex-col flex-1">
                                    <span class="{{ $badge }} text-xs font-bold px-3 py-1 rounded-full inline-block w-fit mb-3">
                                        {{ strtoupper($item->kategori) }}
                                    </span>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $item->judul }}</h4>
                                    <p class="text-sm text-gray-500 mb-3">
                                        <i class="fas fa-calendar-alt mr-1"></i> {{ $item->tanggal->format('d M Y') }}
                                    </p>
                                    @if($item->ringkasan)
                                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $item->ringkasan }}</p>
                                    @endif
                                    <div class="mt-auto">
                                        <a href="{{ route('berita.show', $item->slug) }}"
                                           class="text-koperasi-primary font-medium hover:underline">
                                            Baca Selengkapnya &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Fallback jika belum ada berita --}}
                    <div class="text-center py-16 text-gray-400">
                        <i class="fas fa-newspaper text-5xl mb-4 block"></i>
                        <p class="text-lg">Belum ada berita yang dipublikasikan.</p>
                        <a href="{{ route('berita.index') }}" class="text-koperasi-primary font-semibold mt-2 inline-block hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </section>

        {{-- Visi Misi --}}
        <section id="visi" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-koperasi-secondary">Visi & Misi</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-koperasi-primary">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-eye text-3xl text-koperasi-primary mr-4"></i>
                            <h3 class="text-2xl font-bold text-koperasi-primary">Visi</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed">
                            Menjadi koperasi modern dan terpercaya yang menyejahterakan seluruh anggota sivitas akademika Universitas Muhammadiyah Kendari melalui layanan keuangan yang profesional dan berbasis syariah.
                        </p>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-koperasi-secondary">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-bullseye text-3xl text-koperasi-secondary mr-4"></i>
                            <h3 class="text-2xl font-bold text-koperasi-secondary">Misi</h3>
                        </div>
                        <ul class="text-gray-600 space-y-3">
                            <li class="flex items-start"><i class="fas fa-check-circle text-koperasi-primary mt-1 mr-3 flex-shrink-0"></i> Memberikan layanan simpanan dan pinjaman yang transparan dan adil.</li>
                            <li class="flex items-start"><i class="fas fa-check-circle text-koperasi-primary mt-1 mr-3 flex-shrink-0"></i> Meningkatkan kesejahteraan anggota melalui pembagian SHU yang merata.</li>
                            <li class="flex items-start"><i class="fas fa-check-circle text-koperasi-primary mt-1 mr-3 flex-shrink-0"></i> Mengelola keuangan koperasi secara profesional, akuntabel, dan berkelanjutan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- Kontak --}}
        <section id="kontak" class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-koperasi-secondary">Hubungi Kami</h2>
                <div class="bg-gray-50 rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-semibold text-koperasi-primary mb-6">
                        <i class="fas fa-map-marker-alt mr-2"></i>Kantor Pusat
                    </h3>
                    <ul class="space-y-5 text-gray-600 text-lg">
                        <li class="flex items-center">
                            <i class="fas fa-university text-koperasi-secondary w-6 mr-4"></i>
                            Universitas Muhammadiyah Kendari, Sulawesi Tenggara
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt text-koperasi-secondary w-6 mr-4"></i>
                            (0401) 000-0000
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-koperasi-secondary w-6 mr-4"></i>
                            ksa@umkendari.ac.id
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock text-koperasi-secondary w-6 mr-4"></i>
                            Senin – Jumat: 08.00 – 16.00 WITA
                        </li>
                    </ul>
                    <div class="mt-8 rounded-xl overflow-hidden shadow-md">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6597.232363906566!2d122.50626137678185!3d-3.99870334461061!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d98ecde057e49d9%3A0xee318b8f3b9185ca!2sUniversitas%20Muhammadiyah%20Kendari!5e1!3m2!1sid!2sid!4v1776779979104!5m2!1sid!2sid"
                            width="100%" height="320" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" class="w-full">
                        </iframe>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

