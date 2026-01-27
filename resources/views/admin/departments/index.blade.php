<h2>Departments</h2>

<a href="{{ route('admin.departments.create') }}">Add Department</a>

<table border="1">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Action</th>
    </tr>

    @foreach($departments as $department)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $department->name }}</td>
        <td>
            <a href="{{ route('admin.departments.edit', $department) }}">Edit</a>

            <form action="{{ route('admin.departments.destroy', $department) }}"
                  method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
