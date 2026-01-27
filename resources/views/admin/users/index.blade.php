<h2>Employees</h2>

<a href="{{ route('admin.users.create') }}">Add Employee</a>

<table border="1">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Department</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    @foreach($users as $user)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ ucfirst($user->role) }}</td>
        <td>{{ $user->department?->name ?? 'N/A' }}</td>
        <td>{{ $user->status }}</td>
        <td>
            <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Delete user?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
