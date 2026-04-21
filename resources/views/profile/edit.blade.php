@extends('layouts.users')

@section('konten')
<div class="flex-1 flex flex-col overflow-hidden">
    @include('components.navbar')
    @include('components.toast')

    <main class="content-area flex-1 overflow-y-auto p-6">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi akun dan kata sandi Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Form Informasi Profil --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="font-bold text-lg"><i class="fas fa-user-edit mr-2"></i>Informasi Profil</h4>
                    <p class="text-sm text-green-100 mt-0.5">Perbarui nama dan alamat email akun Anda</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        {{-- Nama --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                                Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $user->name) }}"
                                required autofocus autocomplete="name"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none {{ $errors->has('name') ? 'border-red-500' : '' }}">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}"
                                required autocomplete="username"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none {{ $errors->has('email') ? 'border-red-500' : '' }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit"
                                class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>

                            @if (session('status') === 'profile-updated')
                                <span class="text-sm text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Tersimpan.
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Form Ganti Password --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-green-600 text-white">
                    <h4 class="font-bold text-lg"><i class="fas fa-lock mr-2"></i>Ganti Kata Sandi</h4>
                    <p class="text-sm text-green-100 mt-0.5">Gunakan kata sandi yang panjang dan acak untuk keamanan</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Password Saat Ini --}}
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1">
                                Kata Sandi Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password"
                                    autocomplete="current-password"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none {{ $errors->updatePassword->has('current_password') ? 'border-red-500' : '' }}">
                                <button type="button" onclick="togglePwd('current_password','icon_cur')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye text-sm" id="icon_cur"></i>
                                </button>
                            </div>
                            @if($errors->updatePassword->has('current_password'))
                                <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-1">
                                Kata Sandi Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="new_password" name="password"
                                    autocomplete="new-password"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none {{ $errors->updatePassword->has('password') ? 'border-red-500' : '' }}">
                                <button type="button" onclick="togglePwd('new_password','icon_new')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye text-sm" id="icon_new"></i>
                                </button>
                            </div>
                            @if($errors->updatePassword->has('password'))
                                <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">
                                Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    autocomplete="new-password"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-500' : '' }}">
                                <button type="button" onclick="togglePwd('password_confirmation','icon_conf')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye text-sm" id="icon_conf"></i>
                                </button>
                            </div>
                            @if($errors->updatePassword->has('password_confirmation'))
                                <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit"
                                class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">
                                <i class="fas fa-key mr-1"></i> Perbarui Kata Sandi
                            </button>

                            @if (session('status') === 'password-updated')
                                <span class="text-sm text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Tersimpan.
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </main>
</div>
@endsection

@section('js')
<script>
    function togglePwd(inputId, iconId) {
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
