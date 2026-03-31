<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Roles Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                {{-- Create Role Button --}} @can('create roles')
                <div class="mb-4">
                    <a
                        href="{{ route('roles.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 no-underline">
                        Add New Role
                    </a>
                </div>
                @endcan {{-- Roles Table --}}
                <table
                    class="min-w-full border border-gray-200 divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Role
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Permissions
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($roles as $role)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                    <a href="{{ route('roles.show', $role->id) }}"
                    class="px-3 py-1 bg-blue-100 text-blue-700 border border-blue-600 rounded hover:bg-blue-600 hover:text-white no-underline shadow-sm">
                        View Permissions
                    </a>
                </td>
                <td class="px-6 py-4 text-right text-sm">
                    <div class="flex justify-end items-center gap-2">
                        @can('edit roles')
                            <a href="{{ route('roles.edit', $role->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-green-600 text-black text-xs font-medium rounded-md shadow hover:bg-green-700 transition no-underline">
                                Edit
                            </a>
                        @endcan

                        @can('delete roles')
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md shadow hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">{{ $roles->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>