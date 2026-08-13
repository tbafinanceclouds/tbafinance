<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex min-h-screen">
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4 text-xl font-bold">TBA Finance</div>
            <ul>
                <li><a href="/dashboard" class="block p-4 hover:bg-gray-700">Dashboard</a></li>
                <li><a href="{{ route('members.index') }}" class="block p-4 hover:bg-gray-700">Members</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left p-4 hover:bg-gray-700">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="flex-1 bg-gray-100">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6">Member Details</h1>

                <div class="bg-white rounded shadow p-6 max-w-2xl">
                    <p><strong>Name:</strong> {{ $member->first_name }} {{ $member->last_name }}</p>
                    <p><strong>Email:</strong> {{ $member->email }}</p>
                    <p><strong>Phone:</strong> {{ $member->phone }}</p>
                    <p><strong>National ID:</strong> {{ $member->national_id }}</p>
                    <p><strong>Date of Birth:</strong> {{ $member->date_of_birth }}</p>
                    <p><strong>Address:</strong> {{ $member->address }}</p>
                    <p><strong>Employer:</strong> {{ $member->employer }}</p>

                    <div class="mt-4">
                        <a href="{{ route('members.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>