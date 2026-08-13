<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4 text-xl font-bold">TBA Finance</div>
            <ul>
                <li><a href="/dashboard" class="block p-4 hover:bg-gray-700">Dashboard</a></li>
                <li><a href="{{ route('members.index') }}" class="block p-4 bg-gray-700">Members</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left p-4 hover:bg-gray-700">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-gray-100">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Members</h1>
                    <a href="{{ route('members.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Member</a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white rounded shadow overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Name</th>
                                <th class="p-3 text-left">Email</th>
                                <th class="p-3 text-left">Phone</th>
                                <th class="p-3 text-left">NIN</th>
                                <th class="p-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr class="border-t">
                                <td class="p-3">{{ $member->id }}</td>
                                <td class="p-3">{{ $member->first_name }} {{ $member->last_name }}</td>
                                <td class="p-3">{{ $member->email }}</td>
                                <td class="p-3">{{ $member->phone }}</td>
                                <td class="p-3">{{ $member->national_id }}</td>
                                <td class="p-3">
                                    <a href="{{ route('members.show', $member) }}" class="text-blue-500 hover:underline">View</a>
                                    <a href="{{ route('members.edit', $member) }}" class="text-green-500 hover:underline ml-2">Edit</a>
                                    <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-4">
                        {{ $members->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>