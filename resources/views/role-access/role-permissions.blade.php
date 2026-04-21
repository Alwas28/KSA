@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Role Permission</h2>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-simkop-green text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama Role</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Display Name</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Jumlah Permission</th>
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
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-key mr-2"></i>
                                            {{ $role->permissions_count }}
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
                                        <button onclick="openManagePermissionModal({{ $role->id }})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm" title="Kelola Permission">
                                            <i class="fas fa-key mr-1"></i>
                                            Kelola Permission
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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

    <!-- Modal Manage Permission -->
    <div id="managePermissionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
                <div class="sticky top-0 bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">Kelola Permission Role</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="managePermissionForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">Aktifkan atau nonaktifkan permission untuk role ini:</p>
                        <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto">
                            @forelse($permissions as $module => $modulePermissions)
                                <div class="mb-4">
                                    <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase bg-gray-100 px-3 py-2 rounded">
                                        {{ $module ?: 'Umum' }}
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 ml-3">
                                        @foreach($modulePermissions as $permission)
                                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                <span class="text-xs text-gray-900 flex-1 mr-2">{{ $permission->display_name }}</span>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="sr-only peer permission-checkbox" data-permission-id="{{ $permission->id }}">
                                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm text-center py-4">Belum ada permission tersedia</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function openManagePermissionModal(roleId) {
            try {
                const response = await fetch(`/role-permissions/${roleId}`);
                const data = await response.json();

                document.getElementById('modalTitle').textContent = `Kelola Permission - ${data.role_name}`;
                document.getElementById('managePermissionForm').action = `/role-permissions/${roleId}`;

                // Uncheck all first
                document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);

                // Check role's permissions
                data.permission_ids.forEach(permissionId => {
                    const checkbox = document.querySelector(`input[data-permission-id="${permissionId}"]`);
                    if (checkbox) checkbox.checked = true;
                });

                const modal = document.getElementById('managePermissionModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data role');
            }
        }

        function closeModal() {
            const modal = document.getElementById('managePermissionModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('managePermissionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
