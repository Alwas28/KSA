@extends('layouts.users')

@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
    @include('components.navbar')
    @include('components.toast')

    <main class="content-area flex-1 overflow-y-auto p-6">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Riwayat SHU Saya</h2>
            <p class="text-sm text-gray-500 mt-1">Sisa Hasil Usaha yang diterima setiap tahun</p>
        </div>

        @if(!$anggota)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                    <p class="text-yellow-700 text-sm font-medium">Data anggota tidak ditemukan untuk akun Anda.</p>
                </div>
            </div>
        @else
            {{-- Info Anggota --}}
            <div class="bg-white rounded-xl shadow-md p-5 mb-6 flex items-center gap-4">
                <div class="h-14 w-14 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-green-700 font-bold text-xl">{{ strtoupper(substr($anggota->nama, 0, 2)) }}</span>
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-lg">{{ $anggota->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $anggota->no_anggota }}</p>
                </div>
                <div class="ml-auto text-right">
                    <p class="text-xs text-gray-500">Total SHU Diterima</p>
                    <p class="text-xl font-bold text-green-700">
                        Rp {{ number_format($riwayat->sum('jumlah'), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="font-bold text-lg"><i class="fas fa-history mr-2"></i>Riwayat Penerimaan SHU</h4>
                </div>

                @if($riwayat->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-5xl mb-4 text-gray-300 block"></i>
                        <p class="text-lg">Belum ada riwayat SHU</p>
                        <p class="text-sm text-gray-400 mt-1">Data SHU akan muncul setelah pembagian SHU dilakukan</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3 text-left">No</th>
                                    <th class="px-6 py-3 text-center">Tahun</th>
                                    <th class="px-6 py-3 text-right">Jumlah SHU</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($riwayat as $i => $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-blue-100 text-blue-700 text-sm font-bold px-4 py-1 rounded-full">
                                                {{ $item->shu->tahun }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold text-green-700 text-base">
                                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-green-50">
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-right font-bold text-gray-700">Total</td>
                                    <td class="px-6 py-4 text-right font-bold text-green-700 text-base">
                                        Rp {{ number_format($riwayat->sum('jumlah'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        @endif

    </main>
</div>
@endsection
