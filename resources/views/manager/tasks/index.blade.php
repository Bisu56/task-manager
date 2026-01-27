<h2>My Department Tasks</h2>
<a href="{{ route('manager.tasks.create') }}">Create Task</a>

<table border="1">
    <tr>
        <th>Title</th>
        <th>Assigned To</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    @foreach($tasks as $task)
    <tr>
        <td>{{ $task->title }}</td>
        <td>{{ $task->assignedUser?->name ?? 'Unassigned' }}</td>
        <td>{{ $task->status }}</td>
        <td>
            <a href="{{ route('manager.tasks.edit', $task) }}">Edit</a>
            <form method="POST" action="{{ route('manager.tasks.destroy', $task) }}">
                @csrf
                @method('DELETE')
                <button>Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
