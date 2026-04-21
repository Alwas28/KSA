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
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full grid lg:grid-cols-2 gap-8 items-center">

        {{-- Kolom Kiri: Branding --}}
        <div class="hidden lg:block">
            <div class="text-center lg:text-left">
                <div class="flex justify-center lg:justify-start mb-6">
                    <i class="fas fa-handshake text-koperasi-primary text-6xl"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                    Selamat Datang di <br>
                    <span class="text-koperasi-primary">Koperasi Surya Amaliah</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8">
                    Masuk ke akun Anda untuk mengakses layanan keuangan syariah dan informasi keanggotaan.
                </p>
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/green-energy-4991727-4177939.png"
                         alt="Koperasi Illustration"
                         class="w-full h-auto rounded-xl">
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Login --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-10">

            <div class="mb-8 text-center lg:hidden">
                <i class="fas fa-handshake text-koperasi-primary text-5xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900">Koperasi Surya Amaliah</h2>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Masuk Akun</h2>
                <p class="text-gray-600">Gunakan email dan password yang terdaftar</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.action') }}" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-koperasi-primary"></i>Email
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-koperasi-primary focus:border-koperasi-primary transition duration-150 {{ $errors->has('email') ? 'border-red-500' : '' }}"
                           placeholder="Masukkan email Anda">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-koperasi-primary"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-koperasi-primary focus:border-koperasi-primary transition duration-150 {{ $errors->has('password') ? 'border-red-500' : '' }}"
                               placeholder="Masukkan password Anda">
                        <button type="button"
                                onclick="togglePassword('password','toggleIcon')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                               class="rounded border-gray-300 text-koperasi-primary focus:ring-koperasi-primary">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm font-semibold text-koperasi-secondary hover:text-koperasi-primary transition duration-150">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full flex justify-center items-center bg-koperasi-primary text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-koperasi-primary transition duration-300 text-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Atau</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('home') }}"
                       class="w-full flex justify-center items-center border-2 border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-300">
                        <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>

            {{-- Link ke Register --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                       class="font-semibold text-koperasi-secondary hover:text-koperasi-primary transition duration-150">
                        Daftar di sini
                    </a>
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-shield-alt mr-1"></i> Data Anda dilindungi dengan enkripsi SSL
                </p>
            </div>
        </div>

    </div>
</section>
@endsection

@section('js')
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection
