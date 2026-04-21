@extends('layouts.users')

@section('css')
<style>
    .content-area::-webkit-scrollbar { width: 8px; }
    .content-area::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 10px; }
</style>
@endsection

@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
    @include('components.navbar')
    @include('components.toast')

    <main class="content-area flex-1 overflow-y-auto p-6">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Ketua</h2>
            <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan kondisi koperasi.</p>
        </div>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">

            <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Anggota Aktif</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($totalAnggota) }}</p>
                    <p class="text-xs text-gray-400 mt-1">anggota terdaftar</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-users text-2xl text-green-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Simpanan</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1">Rp {{ number_format($totalSimpanan / 1000000, 1, ',', '.') }} Jt</p>
                    <p class="text-xs text-gray-400 mt-1">{{ number_format($totalSimpanan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-coins text-2xl text-blue-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-500 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pinjaman Berjalan</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($pinjamanBerjalan) }}</p>
                    <p class="text-xs mt-1">
                        @if($pinjamanMenunggu > 0)
                            <span class="text-yellow-600 font-semibold">{{ $pinjamanMenunggu }} menunggu persetujuan</span>
                        @else
                            <span class="text-gray-400">tidak ada pengajuan baru</span>
                        @endif
                    </p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-hand-holding-usd text-2xl text-yellow-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border-l-4 border-purple-500 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">SHU {{ $shuTerbaru?->tahun ?? '-' }}</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1">
                        @if($shuTerbaru && $shuTerbaru->total_shu > 0)
                            Rp {{ number_format($shuTerbaru->total_shu / 1000000, 1, ',', '.') }} Jt
                        @elseif($shuTerbaru)
                            Rp 0
                        @else
                            -
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mt-1">total SHU periode terbaru</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-chart-pie text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        {{-- Row 2: Simpanan per Jenis + Berita --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Simpanan per Jenis --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-blue-600 text-white">
                    <h4 class="font-bold text-base"><i class="fas fa-piggy-bank mr-2"></i>Komposisi Simpanan per Jenis</h4>
                </div>
                <div class="p-6">
                    @php $grandTotal = $simpananPerJenis->sum('total') ?: 1; @endphp
                    @forelse($simpananPerJenis as $js)
                        @php $pct = round(($js->total ?? 0) / $grandTotal * 100, 1); @endphp
                        <div class="mb-5 last:mb-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-gray-700">{{ $js->nama_jenis }}</span>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-blue-600">Rp {{ number_format($js->total ?? 0, 0, ',', '.') }}</span>
                                    <span class="text-xs text-gray-400 ml-2">{{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 py-8 text-sm">Belum ada data simpanan</p>
                    @endforelse

                    <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Total Keseluruhan</span>
                        <span class="text-base font-extrabold text-gray-900">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Berita Terbaru --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="font-bold text-base"><i class="fas fa-newspaper mr-2"></i>Berita & Pengumuman Terbaru</h4>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($beritaTerbaru as $b)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 px-2 py-0.5 rounded text-xs font-semibold
                                    {{ $b->kategori === 'Pengumuman' ? 'bg-yellow-100 text-yellow-700' :
                                       ($b->kategori === 'Artikel' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                    {{ $b->kategori }}
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $b->judul }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $b->tanggal?->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center text-gray-400 text-sm">
                            <i class="fas fa-newspaper text-3xl mb-2 block text-gray-300"></i>
                            Belum ada berita dipublikasikan
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Info Keuangan Ringkasan --}}
        @if($shuTerbaru)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 text-white">
                <h4 class="font-bold text-base"><i class="fas fa-chart-pie mr-2"></i>Informasi SHU Tahun {{ $shuTerbaru->tahun }}</h4>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Total SHU</p>
                    <p class="text-xl font-extrabold text-purple-700">Rp {{ number_format($shuTerbaru->total_shu, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Alokasi Anggota</p>
                    <p class="text-xl font-extrabold text-green-700">Rp {{ number_format($shuTerbaru->alokasi_anggota, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Dana Cadangan</p>
                    <p class="text-xl font-extrabold text-blue-700">Rp {{ number_format($shuTerbaru->alokasi_cadangan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @endif

    </main>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('sidebar-toggle');
        const closeButton = document.getElementById('sidebar-close');
        const backdrop = document.getElementById('backdrop');

        const toggleSidebar = () => {
            const isOpen = sidebar.classList.contains('translate-x-0');
            if (isOpen) {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
            }
        };

        if (toggleButton) toggleButton.addEventListener('click', toggleSidebar);
        if (closeButton) closeButton.addEventListener('click', toggleSidebar);
        if (backdrop) backdrop.addEventListener('click', toggleSidebar);

        sidebar?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) toggleSidebar();
            });
        });

        document.querySelectorAll('[data-submenu-toggle]').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = toggle.getAttribute('data-submenu-toggle');
                const submenu = document.getElementById(`${targetId}-submenu`);
                const arrow = document.querySelector(`[data-arrow="${targetId}"]`);
                if (submenu && arrow) {
                    const isHidden = submenu.classList.contains('hidden');
                    document.querySelectorAll('[data-submenu-toggle]').forEach(t => {
                        const tid = t.getAttribute('data-submenu-toggle');
                        const sm = document.getElementById(`${tid}-submenu`);
                        const ar = document.querySelector(`[data-arrow="${tid}"]`);
                        if (sm && ar && !sm.classList.contains('hidden')) {
                            sm.classList.add('hidden');
                            ar.classList.remove('rotate-180');
                        }
                    });
                    if (isHidden) {
                        submenu.classList.remove('hidden');
                        arrow.classList.add('rotate-180');
                    }
                }
            });
        });
    });
</script>
@endsection
