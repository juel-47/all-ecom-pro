<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Edit Role: {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Role Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Role Name</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}"
                               class="mt-1 block w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                     {{-- Permissions --}}
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-4">Permissions</label>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Permission Group</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Available Permissions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{-- Orders --}}
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Orders</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-3">
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'orders')) as $permission)
                                <label class="flex items-center space-x-2 border px-3 py-1 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ ucfirst($permission->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                </tr>

                {{-- Products --}}
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Products</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-3">
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'products')) as $permission)
                                <label class="flex items-center space-x-2 border px-3 py-1 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ ucfirst($permission->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                </tr>

                {{-- Users --}}
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Users</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-3">
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'users')) as $permission)
                                <label class="flex items-center space-x-2 border px-3 py-1 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ ucfirst($permission->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                </tr>

                {{-- Invoices --}}
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">Invoices</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-3">
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'invoices')) as $permission)
                                <label class="flex items-center space-x-2 border px-3 py-1 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ ucfirst($permission->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
                    {{-- Buttons --}}
                        <div class="flex items-center space-x-6">
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                        Update Role
                    </button>
                    <a href="{{ route('roles.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1 no-underline">
                        Cancel
                    </a>
                </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
