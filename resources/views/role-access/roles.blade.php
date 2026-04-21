@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Role</h2>
                <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Role</span>
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama Role</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Display Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Permissions</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Users</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($roles as $index => $role)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-gray-900">{{ $role->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $role->display_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ Str::limit($role->description ?? '-', 50) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-key mr-2"></i>
                                            {{ $role->permissions_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-users mr-2"></i>
                                            {{ $role->users_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($role->is_active)
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
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            @if(auth()->user()->hasPermission('role_permission.edit'))
                                                <a href="{{ route('role-permissions.show', $role) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition-colors duration-200 text-sm flex items-center gap-1" title="Kelola Permission">
                                                    <i class="fas fa-key"></i>
                                                    <span>Permission</span>
                                                </a>
                                            @endif
                                            <button onclick="openEditModal({{ $role->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors duration-200" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data role</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Create/Edit Role -->
    <div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 id="modalTitle" class="text-xl font-bold">Tambah Role</h3>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="roleForm" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="roleMethod" name="_method" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Role <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="roleName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-simkop-green focus:border-transparent" placeholder="Contoh: admin">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Display Name <span class="text-red-500">*</span></label>
                        <input type="text" name="display_name" id="roleDisplayName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-simkop-green focus:border-transparent" placeholder="Contoh: Administrator">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="roleDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-simkop-green focus:border-transparent" placeholder="Deskripsi role..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="roleIsActive" value="1" checked class="w-5 h-5 text-simkop-green rounded focus:ring-simkop-green">
                        <span class="text-sm font-semibold text-gray-700">Role Aktif</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-semibold">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            const modal = document.getElementById('roleModal');

            document.getElementById('modalTitle').textContent = 'Tambah Role';
            document.getElementById('roleForm').action = '{{ route("roles.store") }}';
            document.getElementById('roleMethod').value = 'POST';
            document.getElementById('roleName').value = '';
            document.getElementById('roleDisplayName').value = '';
            document.getElementById('roleDescription').value = '';
            document.getElementById('roleIsActive').checked = true;

            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }

        async function openEditModal(roleId) {
            try {
                const response = await fetch(`/roles/${roleId}`);
                const role = await response.json();

                document.getElementById('modalTitle').textContent = 'Edit Role';
                document.getElementById('roleForm').action = `/roles/${roleId}`;
                document.getElementById('roleMethod').value = 'PUT';
                document.getElementById('roleName').value = role.name;
                document.getElementById('roleDisplayName').value = role.display_name;
                document.getElementById('roleDescription').value = role.description || '';
                document.getElementById('roleIsActive').checked = role.is_active;

                const modal = document.getElementById('roleModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data role');
            }
        }

        function closeModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('roleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
