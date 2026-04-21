@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Permission</h2>
                <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Permission</span>
                </button>
            </div>

            @forelse($permissions as $module => $modulePermissions)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="bg-simkop-green text-white px-6 py-3">
                        <h3 class="text-lg font-bold uppercase">
                            <i class="fas fa-folder mr-2"></i>
                            {{ $module ?: 'Umum' }}
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Permission</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Display Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Roles</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($modulePermissions as $index => $permission)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900">{{ $permission->name }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $permission->display_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ Str::limit($permission->description ?? '-', 50) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-user-shield mr-2"></i>
                                                {{ $permission->roles_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($permission->is_active)
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
                                                <button onclick="openEditModal({{ $permission->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors duration-200" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permission ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">Belum ada data permission</p>
                </div>
            @endforelse
        </main>
    </div>

    <!-- Modal Create/Edit Permission -->
    <div id="permissionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full transform transition-all">
                <div class="bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">Tambah Permission</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

            <form id="permissionForm" method="POST" class="p-6">
                @csrf
                <input type="hidden" id="permissionMethod" name="_method" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Permission <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="permissionName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-simkop-green focus:border-transparent" placeholder="Contoh: create-user">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Display Name <span class="text-red-500">*</span></label>
                        <input type="text" name="display_name" id="permissionDisplayName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-simkop-green focus:border-transparent" placeholder="Contoh: Create User">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Module/Kategori</label>
                    <input type="text" name="module" id="permissionModule" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-simkop-green focus:border-transparent" placeholder="Contoh: users, products">
                    <p class="text-xs text-gray-500 mt-1">Digunakan untuk mengelompokkan permissions</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="permissionDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-simkop-green focus:border-transparent" placeholder="Deskripsi permission..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="permissionIsActive" value="1" checked class="w-5 h-5 text-simkop-green rounded focus:ring-simkop-green">
                        <span class="text-sm font-semibold text-gray-700">Permission Aktif</span>
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
            const modal = document.getElementById('permissionModal');

            document.getElementById('modalTitle').textContent = 'Tambah Permission';
            document.getElementById('permissionForm').action = '{{ route("permissions.store") }}';
            document.getElementById('permissionMethod').value = 'POST';
            document.getElementById('permissionName').value = '';
            document.getElementById('permissionDisplayName').value = '';
            document.getElementById('permissionModule').value = '';
            document.getElementById('permissionDescription').value = '';
            document.getElementById('permissionIsActive').checked = true;

            modal.classList.remove('hidden');
            modal.style.display = 'block';
        }

        async function openEditModal(permissionId) {
            try {
                const response = await fetch(`/permissions/${permissionId}`);
                const permission = await response.json();

                document.getElementById('modalTitle').textContent = 'Edit Permission';
                document.getElementById('permissionForm').action = `/permissions/${permissionId}`;
                document.getElementById('permissionMethod').value = 'PUT';
                document.getElementById('permissionName').value = permission.name;
                document.getElementById('permissionDisplayName').value = permission.display_name;
                document.getElementById('permissionModule').value = permission.module || '';
                document.getElementById('permissionDescription').value = permission.description || '';
                document.getElementById('permissionIsActive').checked = permission.is_active;

                const modal = document.getElementById('permissionModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data permission');
            }
        }

        function closeModal() {
            const modal = document.getElementById('permissionModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('permissionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
