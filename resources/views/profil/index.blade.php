@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Profil Anggota</h2>
                        <p class="text-sm text-gray-600 mt-1">Detail informasi anggota koperasi</p>
                    </div>
                    @if(auth()->user()->email == $anggota->email)
                        <a href="{{ route('profil-anggota.edit') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-edit"></i>
                            <span>Edit Profil</span>
                        </a>
                    @else
                        <a href="{{ route('anggota.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8 text-center">
                            <div class="inline-block">
                                <div class="h-32 w-32 bg-white rounded-full flex items-center justify-center mx-auto shadow-lg">
                                    <span class="text-green-600 font-bold text-4xl">{{ strtoupper(substr($anggota->nama, 0, 2)) }}</span>
                                </div>
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-white">{{ $anggota->nama }}</h3>
                            <p class="text-green-100 text-sm">{{ $anggota->no_anggota }}</p>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-sm font-semibold text-gray-600">Status Aktif</span>
                                @if($anggota->aktif == 'Y')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                                <span class="text-sm font-semibold text-gray-600">Status Anggota</span>
                                @if($anggota->statusAnggota)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $anggota->statusAnggota->nama_status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        <i class="fas fa-minus-circle mr-1"></i>
                                        Belum ada
                                    </span>
                                @endif
                            </div>

                            <div class="pt-3">
                                <span class="text-sm font-semibold text-gray-600">Bergabung Sejak</span>
                                <p class="text-gray-900 mt-1">{{ $anggota->created_at->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-green-600 text-white">
                            <h4 class="text-lg font-bold">
                                <i class="fas fa-id-card mr-2"></i>
                                Informasi Detail
                            </h4>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Nama Lengkap</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $anggota->nama }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">No Anggota</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $anggota->no_anggota }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Email</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $anggota->email }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Tempat Lahir</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $anggota->tempat_lahir ?? '-' }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Tanggal Lahir</label>
                                    <p class="mt-1 text-gray-900 font-medium">
                                        {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Jenis Kelamin</label>
                                    <p class="mt-1 text-gray-900 font-medium">
                                        @if($anggota->jenis_kelamin)
                                            {{ $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Pekerjaan</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $anggota->pekerjaan ?? '-' }}</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-sm font-semibold text-gray-600">Alamat</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $anggota->alamat ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information (if status description exists) -->
                    @if($anggota->statusAnggota && $anggota->statusAnggota->deskripsi)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-blue-700">
                                        Informasi Status: {{ $anggota->statusAnggota->nama_status }}
                                    </p>
                                    <p class="mt-1 text-sm text-blue-600">
                                        {{ $anggota->statusAnggota->deskripsi }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== SIMPANAN ===== --}}
            <div class="mt-6">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-green-600 text-white flex items-center justify-between">
                        <h4 class="text-lg font-bold">
                            <i class="fas fa-piggy-bank mr-2"></i>
                            Data Simpanan
                        </h4>
                        @php
                            $totalSimpanan = $anggota->simpanan->sum('nominal');
                        @endphp
                        <span class="text-sm font-semibold bg-white text-green-700 px-3 py-1 rounded-full">
                            Total: Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($anggota->simpanan->isEmpty())
                        <div class="p-6 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>
                            Belum ada data simpanan
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-3 text-left">No</th>
                                        <th class="px-6 py-3 text-left">Jenis Simpanan</th>
                                        <th class="px-6 py-3 text-left">Keterangan</th>
                                        <th class="px-6 py-3 text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($anggota->simpanan as $i => $simpanan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3 text-gray-500">{{ $i + 1 }}</td>
                                            <td class="px-6 py-3 font-medium text-gray-800">
                                                {{ $simpanan->jenisSimpanan->nama_jenis ?? '-' }}
                                            </td>
                                            <td class="px-6 py-3 text-gray-600">{{ $simpanan->keterangan ?? '-' }}</td>
                                            <td class="px-6 py-3 text-right font-semibold text-green-700">
                                                Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-green-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right font-bold text-gray-700">Total</td>
                                        <td class="px-6 py-3 text-right font-bold text-green-700">
                                            Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== PINJAMAN ===== --}}
            <div class="mt-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600 text-white flex items-center justify-between">
                        <h4 class="text-lg font-bold">
                            <i class="fas fa-hand-holding-usd mr-2"></i>
                            Data Pinjaman
                        </h4>
                        <span class="text-sm font-semibold bg-white text-blue-700 px-3 py-1 rounded-full">
                            {{ $anggota->pinjaman->count() }} pinjaman
                        </span>
                    </div>

                    @if($anggota->pinjaman->isEmpty())
                        <div class="p-6 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>
                            Belum ada data pinjaman
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-3 text-left">No</th>
                                        <th class="px-6 py-3 text-left">Tgl Pengajuan</th>
                                        <th class="px-6 py-3 text-right">Pokok Pinjaman</th>
                                        <th class="px-6 py-3 text-center">Lama Angsuran</th>
                                        <th class="px-6 py-3 text-right">Angsuran/Bulan</th>
                                        <th class="px-6 py-3 text-center">Sisa</th>
                                        <th class="px-6 py-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($anggota->pinjaman as $i => $pinjaman)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-3 text-gray-500">{{ $i + 1 }}</td>
                                            <td class="px-6 py-3 text-gray-700">
                                                {{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-3 text-right font-semibold text-gray-800">
                                                Rp {{ number_format($pinjaman->pokok_pinjaman, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-3 text-center text-gray-700">
                                                {{ $pinjaman->lama_angsuran }} bulan
                                            </td>
                                            <td class="px-6 py-3 text-right text-gray-700">
                                                Rp {{ number_format($pinjaman->angsuran_per_bulan, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-3 text-center text-gray-700">
                                                {{ $pinjaman->sisa_angsuran }} bulan
                                            </td>
                                            <td class="px-6 py-3 text-center">
                                                @php
                                                    $statusConfig = [
                                                        'diajukan'  => ['bg-yellow-100 text-yellow-800', 'fa-clock', 'Diajukan'],
                                                        'disetujui' => ['bg-blue-100 text-blue-800',   'fa-check',       'Disetujui'],
                                                        'ditolak'   => ['bg-red-100 text-red-800',     'fa-times',       'Ditolak'],
                                                        'lunas'     => ['bg-green-100 text-green-800', 'fa-check-double','Lunas'],
                                                    ];
                                                    $cfg = $statusConfig[$pinjaman->status] ?? ['bg-gray-100 text-gray-800', 'fa-question', $pinjaman->status];
                                                @endphp
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $cfg[0] }}">
                                                    <i class="fas {{ $cfg[1] }}"></i>
                                                    {{ $cfg[2] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection
