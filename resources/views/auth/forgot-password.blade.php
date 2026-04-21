@extends('layouts.home')

@section('css')
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'koperasi-primary': '#059669',
                    'koperasi-secondary': '#1d4ed8',
                }
            }
        }
    }
</script>
@endsection

@section('konten')
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-10">
            <div class="text-center mb-8">
                <i class="fas fa-key text-koperasi-primary text-6xl mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
                <p class="text-gray-600">Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link reset password.</p>
            </div>

            @if(session('status'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-700 text-sm">{{ session('status') }}</p>
                </div>
            </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-koperasi-primary"></i>Alamat Email
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           autofocus
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-koperasi-primary focus:border-koperasi-primary transition duration-150 @error('email') border-red-500 @enderror"
                           placeholder="Masukkan email Anda"
                           value="{{ old('email') }}">
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full flex justify-center items-center bg-koperasi-primary text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koperasi-primary transition duration-300 text-lg">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Link Reset Password
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Atau</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="{{ route('login') }}"
                       class="flex justify-center items-center border-2 border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali Login
                    </a>
                    <a href="{{ route('home') }}"
                       class="flex justify-center items-center border-2 border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-300">
                        <i class="fas fa-home mr-2"></i> Beranda
                    </a>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-500 mr-3 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Catatan Penting:</p>
                            <p class="text-xs text-blue-700 mt-1">
                                Link reset password akan dikirim ke email Anda. Periksa folder inbox atau spam jika tidak menemukannya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
