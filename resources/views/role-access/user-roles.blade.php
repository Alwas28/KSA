@extends('layouts.users')

@section('konten')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('components.navbar')
        @include('components.toast')

        <main class="content-area flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen User Role</h2>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-simkop-green text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama User</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Roles</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $index => $user)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($user->roles as $role)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <i class="fas fa-user-tag mr-1"></i>
                                                    {{ $role->display_name }}
                                                </span>
                                            @empty
                                                <span class="text-sm text-gray-500">Belum ada role</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(auth()->user()->hasPermission('user_role.edit'))
                                            <button onclick="openAssignRoleModal({{ $user->id }})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm" title="Kelola Role">
                                                <i class="fas fa-user-cog mr-1"></i>
                                                Kelola Role
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Belum ada data user</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Assign Role -->
    <div id="assignRoleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full transform transition-all">
                <div class="bg-green-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 id="modalTitle" class="text-xl font-bold">Kelola Role User</h3>
                    <button onclick="closeModal()" class="text-white hover:text-gray-200 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="assignRoleForm" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">Pilih role yang ingin diberikan kepada user:</p>
                        <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto space-y-3">
                            @forelse($roles as $role)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $role->display_name }}</h4>
                                        <p class="text-xs text-gray-600 mt-1">{{ $role->description ?? 'Tidak ada deskripsi' }}</p>
                                        <span class="inline-flex items-center mt-2 px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-key mr-1"></i>
                                            {{ $role->permissions_count }} permissions
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="sr-only peer role-checkbox" data-role-id="{{ $role->id }}">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm text-center py-4">Belum ada role tersedia</p>
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
        async function openAssignRoleModal(userId) {
            try {
                const response = await fetch(`/user-roles/${userId}`);
                const data = await response.json();

                document.getElementById('assignRoleForm').action = `/user-roles/${userId}`;

                // Uncheck all first
                document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = false);

                // Check user's roles
                data.role_ids.forEach(roleId => {
                    const checkbox = document.querySelector(`input[data-role-id="${roleId}"]`);
                    if (checkbox) checkbox.checked = true;
                });

                const modal = document.getElementById('assignRoleModal');
                modal.classList.remove('hidden');
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data user');
            }
        }

        function closeModal() {
            const modal = document.getElementById('assignRoleModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('assignRoleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
