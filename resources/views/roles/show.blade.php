<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Role: {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Permission Groups</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700 w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {{-- Permissions --}}
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @php
                                            $grouped = $role->permissions->groupBy(function($perm) {
                                                return explode(' ', $perm->name)[1] ?? 'Other';
                                            });
                                        @endphp

                                        @foreach ($grouped as $group => $permissions)
                                            <div class="bg-gray-50 rounded-lg p-3 shadow-sm">
                                                <h4 class="text-xs font-semibold text-gray-700 mb-2 uppercase">
                                                    {{ $group }}
                                                </h4>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($permissions as $permission)
                                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">
                                                            {{ ucfirst($permission->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right text-sm align-middle">
    <div class="flex justify-end items-center gap-3">
        @can('edit roles')
            <a href="{{ route('roles.edit', $role->id) }}"
               class="px-3 py-1 text-green-600 border border-green-600 rounded 
                      hover:bg-gray-600 hover:text-white 
                      focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1 
                      transition no-underline shadow-sm">
                Edit
            </a>
        @endcan

        @can('delete roles')
            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" 
                  onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-3 py-1 text-red-600 border border-red-600 rounded 
                               hover:bg-gray-600 hover:text-white 
                               focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1 
                               transition shadow-sm">
                    Delete
                </button>
            </form>
        @endcan
    </div>
</td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
