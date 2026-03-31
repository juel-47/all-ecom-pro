<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users List') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="text-center bg-gray-100">
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Roles</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="text-center">
                                <td class="border px-4 py-2">{{ $user->id }}</td>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">
    @foreach($user->roles as $role)
        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded mr-1">
            {{ $role->name }}
        </span>
    @endforeach
</td>

<td class="border px-4 py-2 flex justify-center space-x-2">
    <!-- Assign Role -->
    <a href="{{ route('users.edit', $user->id) }}"
       class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700 no-underline">
        Assign Roles
    </a>

    <!-- Delete User -->
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
            Delete
        </button>
    </form>
</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

