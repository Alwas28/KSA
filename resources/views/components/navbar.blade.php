<!-- Header Utama -->
        <header class="bg-white shadow-md p-4 flex items-center justify-between flex-shrink-0">
            <!-- Tombol Toggle Sidebar (Hanya Mobile) -->
            <button id="sidebar-toggle" class="md:hidden text-gray-500 hover:text-simkop-green-dark focus:outline-none p-2 rounded-full hover:bg-gray-100 mr-3">
                <i class="fas fa-bars text-lg"></i>
            </button>
            
            <div class="text-xl font-semibold text-gray-800">Koperasi Surya Amaliah</div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-simkop-green-dark focus:outline-none p-2 rounded-full hover:bg-gray-100">
                    <i class="fas fa-bell text-lg"></i>
                </button>
                <div class="relative group">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold cursor-pointer">
                        SA
                    </div>
                    <!-- Menu Dropdown Profil (Opsional) -->
                    <div class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2 z-50">
                        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-simkop-green-light to-simkop-green rounded-t-xl">
                            <p class="font-bold text-sm text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-black/90 mt-1">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('profil-anggota.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-simkop-green-light/10 hover:to-simkop-green/10 hover:text-simkop-green-dark transition-all duration-200 group/item">
                                <i class="fas fa-user mr-3 text-simkop-green group-hover/item:scale-110 transition-transform"></i>
                                <span class="font-medium">Profil Anggota</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-simkop-green-light/10 hover:to-simkop-green/10 hover:text-simkop-green-dark transition-all duration-200 group/item">
                                <i class="fas fa-user-cog mr-3 text-simkop-green group-hover/item:scale-110 transition-transform"></i>
                                <span class="font-medium">Edit Akun</span>
                            </a>
                            <form action="{{ route('logout') }}" method="post" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 rounded-b-xl transition-all duration-200 group/item font-medium">
                                    <i class="fas fa-sign-out-alt mr-3 group-hover/item:scale-110 transition-transform"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>