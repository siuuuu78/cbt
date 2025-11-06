<x-app-layout title="Kelola Instruktur">
    <div class="py-12 max-w-6xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Kelola Instruktur</h1>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->hasRole('instructor'))
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Instruktur</span>
                                @elseif($user->hasRole('admin'))
                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">Admin</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Student</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->hasRole('instructor') && ! $user->hasRole('admin'))
                                    <form method="POST" action="{{ route('admin.instructors.revoke', $user) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm"
                                                onclick="return confirm('Cabut status instruktur?')">
                                            Cabut
                                        </button>
                                    </form>
                                @elseif(! $user->hasRole('instructor') && ! $user->hasRole('admin'))
                                    <form method="POST" action="{{ route('admin.instructors.promote', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:underline text-sm">
                                            Jadikan Instruktur
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
