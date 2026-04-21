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
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
            <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan data koperasi.</p>
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
                    <i class="fas fa-user-check text-2xl text-green-600"></i>
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

        {{-- Row 2: Tabel Pinjaman + Simpanan per Jenis --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white flex justify-between items-center">
                    <h4 class="font-bold text-base"><i class="fas fa-file-invoice-dollar mr-2"></i>Pengajuan Pinjaman Terbaru</h4>
                    @if(auth()->user()->hasPermission('pinjaman.read'))
                    <a href="{{ route('persetujuan-pinjaman.index') }}" class="text-xs text-green-100 hover:text-white transition">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3 text-left">Anggota</th>
                                <th class="px-5 py-3 text-right">Pokok</th>
                                <th class="px-5 py-3 text-center">Angsuran</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pinjamanTerbaru as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $p->anggota->nama ?? '-' }}</td>
                                    <td class="px-5 py-3 text-right text-gray-700">Rp {{ number_format($p->pokok_pinjaman, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-center text-gray-600">{{ $p->lama_angsuran }} bln</td>
                                    <td class="px-5 py-3 text-center">
                                        @php
                                            $statusMap = [
                                                'diajukan'  => ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                                                'disetujui' => ['bg-green-100 text-green-700',   'Disetujui'],
                                                'dicairkan' => ['bg-blue-100 text-blue-700',     'Dicairkan'],
                                                'ditolak'   => ['bg-red-100 text-red-700',       'Ditolak'],
                                            ];
                                            [$cls, $lbl] = $statusMap[$p->status] ?? ['bg-gray-100 text-gray-600', ucfirst($p->status)];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ $lbl }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                                        <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>
                                        Belum ada pengajuan pinjaman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-blue-600 text-white">
                    <h4 class="font-bold text-base"><i class="fas fa-piggy-bank mr-2"></i>Simpanan per Jenis</h4>
                </div>
                <div class="p-5">
                    @php $grandTotal = $simpananPerJenis->sum('total') ?: 1; @endphp
                    @forelse($simpananPerJenis as $js)
                        @php $pct = round(($js->total ?? 0) / $grandTotal * 100, 1); @endphp
                        <div class="mb-4 last:mb-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-gray-700">{{ $js->nama_jenis }}</span>
                                <span class="text-sm font-bold text-blue-600">Rp {{ number_format($js->total ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $pct }}%</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 py-8 text-sm">Belum ada data simpanan</p>
                    @endforelse

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Total</span>
                        <span class="text-sm font-extrabold text-gray-900">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: Berita Terbaru + Aksi Cepat --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white flex justify-between items-center">
                    <h4 class="font-bold text-base"><i class="fas fa-newspaper mr-2"></i>Berita & Pengumuman Terbaru</h4>
                    @if(auth()->user()->hasPermission('berita.read'))
                    <a href="{{ route('manajemen-berita.index') }}" class="text-xs text-green-100 hover:text-white transition">
                        Kelola <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @endif
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

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-700 text-white">
                    <h4 class="font-bold text-base"><i class="fas fa-bolt mr-2"></i>Aksi Cepat</h4>
                </div>
                <div class="p-5 space-y-3">
                    @if(auth()->user()->hasPermission('anggota.create'))
                    <a href="{{ route('anggota.index') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-green-400 hover:bg-green-50 transition group">
                        <div class="bg-green-100 group-hover:bg-green-200 rounded-lg p-2 transition">
                            <i class="fas fa-user-plus text-green-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Tambah Anggota</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('simpanan.create'))
                    <a href="{{ route('simpanan.index') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition group">
                        <div class="bg-blue-100 group-hover:bg-blue-200 rounded-lg p-2 transition">
                            <i class="fas fa-plus-circle text-blue-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Catat Simpanan</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('pinjaman.read'))
                    <a href="{{ route('persetujuan-pinjaman.index') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-yellow-400 hover:bg-yellow-50 transition group">
                        <div class="bg-yellow-100 group-hover:bg-yellow-200 rounded-lg p-2 transition">
                            <i class="fas fa-check-circle text-yellow-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 flex-1">Persetujuan Pinjaman</span>
                        @if($pinjamanMenunggu > 0)
                            <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pinjamanMenunggu }}</span>
                        @endif
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('laporan_shu.read'))
                    <a href="{{ route('shu.index') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-purple-400 hover:bg-purple-50 transition group">
                        <div class="bg-purple-100 group-hover:bg-purple-200 rounded-lg p-2 transition">
                            <i class="fas fa-chart-pie text-purple-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Manajemen SHU</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('berita.create'))
                    <a href="{{ route('manajemen-berita.create') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-green-400 hover:bg-green-50 transition group">
                        <div class="bg-green-100 group-hover:bg-green-200 rounded-lg p-2 transition">
                            <i class="fas fa-pen text-green-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Tulis Berita</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

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
