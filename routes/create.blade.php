<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
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
                <h1 class="text-2xl font-bold mb-6">Add New Member</h1>

                <div class="bg-white rounded shadow p-6 max-w-2xl">
                    <form action="{{ route('members.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700">First Name</label>
                                <input type="text" name="first_name" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700">Last Name</label>
                                <input type="text" name="last_name" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700">Email</label>
                                <input type="email" name="email" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-gray-700">Phone</label>
                                <input type="text" name="phone" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-gray-700">National ID</label>
                                <input type="text" name="national_id" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="w-full border rounded px-3 py-2">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-gray-700">Address</label>
                                <textarea name="address" class="w-full border rounded px-3 py-2"></textarea>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-gray-700">Employer</label>
                                <input type="text" name="employer" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Member</button>
                            <a href="{{ route('members.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>